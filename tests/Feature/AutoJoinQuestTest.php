<?php

namespace Tests\Feature;

use App\Domain\Actions\Testing\AutoManageCampaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Minion;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Domain\Models\Titan;
use App\Domain\Models\Week;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AutoJoinQuestTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->states('as-current', 'adventuring-open')->create();
    }

    /**
     * @test
     */
    public function it_will_create_a_campaign_for_the_squad_with_filled_campaign_stops()
    {
        $squad = SquadFactory::new()->create();
        $continentID = $squad->province->continent_id;
        $alreadyAvailableQuestsCount = Quest::query()->whereHas('province', function (Builder $builder) use ($continentID) {
            $builder->where('continent_id', '=', $continentID);
        })->count();

        $questsPerWeek = $squad->getQuestsPerWeek();
        $sideQuestsPerQuest = $squad->getSideQuestsPerQuest();
        $questsNeeded = $questsPerWeek - $alreadyAvailableQuestsCount;

        if ($questsNeeded > 0) {
            $provinces = Province::query()->where('continent_id', '=', $continentID)->get();

            $sideQuestFactories = collect();
            foreach(range(1, $sideQuestsPerQuest) as $count) {
                $sideQuestFactories->push(SideQuestFactory::new());
            }
            $questFactory = QuestFactory::new()->withSideQuests($sideQuestFactories);

            while($questsNeeded > 0) {
                $provinceID = $provinces->shift()->id;
                $questFactory->withProvinceID($provinceID)->create();
                $questsNeeded--;
            }
        }

        $this->assertNull($squad->getThisWeeksCampaign());


        /** @var AutoManageCampaign $domainAction */
        $domainAction = app(AutoManageCampaign::class);
        $domainAction->execute($squad);

        $squad = $squad->fresh();
        $currentWeekCampaign = $squad->getThisWeeksCampaign();
        $this->assertNotNull($currentWeekCampaign);

        $campaignStops = $currentWeekCampaign->campaignStops;

        $this->assertEquals($questsPerWeek, $campaignStops->count());
        $campaignStops->each(function (CampaignStop $campaignStop) {
            $this->assertGreaterThan(0, $campaignStop->sideQuests->count());
        });
    }
}
