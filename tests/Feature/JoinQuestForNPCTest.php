<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\JoinQuestForNPC;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Continent;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Facades\NPC;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JoinQuestForNPCTest extends TestCase
{
    use DatabaseTransactions;

    protected $currentWeek;

    protected $squad;

    protected $quest;

    public function setUp(): void
    {
        parent::setUp();
        // Need adventuring open to join quests
        $this->currentWeek = factory(Week::class)->states('as-current', 'adventuring-open')->create();

        // Use Fetroya continent so no restrictions
        $continentID = Continent::query()->where('name', '=', Continent::FETROYA)->firstOrFail()->id;
        $provinces = Province::query()->where('continent_id', '=', $continentID)->get();

        $questProvince = $provinces->shuffle()->shift();
        $this->quest = QuestFactory::new()->withProvinceID($questProvince->id)->create();

        $squadProvince = $provinces->shuffle()->shift();
        $this->squad = SquadFactory::new()->atProvince($squadProvince->id)->create();
    }

    /**
     * @return JoinQuestForNPC
     */
    protected function getDomainAction()
    {
        return app(JoinQuestForNPC::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_joining_quest_for_non_npc_squad()
    {
        NPC::shouldReceive('isNPC')->andReturn(false);
        try {
            $this->getDomainAction()->execute($this->squad, $this->quest);
        } catch (\Exception $exception) {
            $this->assertEquals(JoinQuestForNPC::EXCEPTION_CODE_NOT_NPC, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_move_the_npc_to_the_location_of_the_quest()
    {
        $this->assertNotEquals($this->quest->province_id, $this->squad->province_id);

        NPC::shouldReceive('isNPC')->andReturn(true);
        $this->getDomainAction()->execute($this->squad, $this->quest);

        $this->assertEquals($this->quest->province_id, $this->squad->fresh()->province_id);
    }

    /**
     * @test
     */
    public function it_will_join_the_npc_to_the_quest()
    {
        $this->assertNull($this->squad->getThisWeeksCampaign());

        NPC::shouldReceive('isNPC')->andReturn(true);
        $this->getDomainAction()->execute($this->squad, $this->quest);

        $this->squad = $this->squad->fresh();

        $campaign = $this->squad->getThisWeeksCampaign();
        $this->assertNotNull($campaign);

        $this->assertEquals(1, $campaign->campaignStops->count());

        /** @var CampaignStop $campaignStop */
        $campaignStop = $campaign->campaignStops->first();
        $this->assertEquals($this->quest->id, $campaignStop->quest_id);
    }

    /**
     * @test
     */
    public function it_will_join_the_npc_to_the_side_quests_of_the_given_quest()
    {
        $sideQuestFactory = SideQuestFactory::new()->forQuestID($this->quest->id);

        for ($i = 1; $i <= 8; $i++) {
            $sideQuestFactory->create();
        }

        /** @var CampaignStop $campaignStop */
        $campaignStop = $this->squad->getThisWeeksCampaign()->campaignStops->first();
    }
}
