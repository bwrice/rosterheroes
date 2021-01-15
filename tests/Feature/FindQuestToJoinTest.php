<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\ActionTriggers\NPCActionTrigger;
use App\Domain\Actions\NPC\FindQuestsToJoin;
use App\Domain\Models\Continent;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Week;
use App\Facades\NPC;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FindQuestToJoinTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FindQuestsToJoin
     */
    protected function getDomainAction()
    {
        return app(FindQuestsToJoin::class);
    }

    /**
     * @test
     */
    public function it_will_return_quests_and_side_quests_to_join_for_an_npc_without_quests_joined()
    {
        $week = factory(Week::class)->states('as-current', 'adventuring-open')->create();
        $npc = SquadFactory::new()->create();

        /** @var Continent $fetroya */
        $fetroya = Continent::query()->where('name', '=', Continent::FETROYA)->first();
        $provinces = Province::query()->where('continent_id', '=', $fetroya->id)->inRandomOrder()->get();

        for ($i = 1; $i <= $npc->getQuestsPerWeek() + 1; $i++) {
            $quest = QuestFactory::new()->withProvinceID($provinces->shift()->id)->create();
            for ($j = 1; $j <= $npc->getSideQuestsPerQuest() + 1; $j++) {
                SideQuestFactory::new()->forQuestID($quest->id)->create();
            }
        }

        $questArrays = $this->getDomainAction()->execute($npc);

        $this->assertEquals($npc->getQuestsPerWeek(), $questArrays->count());

        $questArrays->each(function ($questToJoinArray) use ($fetroya, $npc) {
            /** @var Quest $quest */
            $quest = $questToJoinArray['quest'];
            $this->assertEquals($fetroya->id, $quest->province->continent_id);

            $this->assertEquals($npc->getSideQuestsPerQuest(), count($questToJoinArray['side_quests']));
            foreach ($questToJoinArray['side_quests'] as $sideQuest) {
                /** @var SideQuest $sideQuest */
                $this->assertEquals($quest->id, $sideQuest->quest_id);
            }
        });
    }

    /**
     * @test
     */
    public function it_will_limit_quest_to_join_for_npc_to_quests_per_week_minus_quests_already_joined()
    {
        $week = factory(Week::class)->states('as-current', 'adventuring-open')->create();

        $npc = SquadFactory::new()->create();
        $campaignStop = CampaignStopFactory::new()
            ->withCampaign(CampaignFactory::new()->withWeekID($week->id)->withSquadID($npc->id))
            ->create();

        /** @var Continent $fetroya */
        $fetroya = Continent::query()->where('name', '=', Continent::FETROYA)->first();
        $provinces = Province::query()->where('continent_id', '=', $fetroya->id)->inRandomOrder()->get();

        for ($i = 1; $i <= $npc->getQuestsPerWeek() + 1; $i++) {
            $quest = QuestFactory::new()->withProvinceID($provinces->shift()->id)->create();
            for ($j = 1; $j <= $npc->getSideQuestsPerQuest() + 1; $j++) {
                SideQuestFactory::new()->forQuestID($quest->id)->create();
            }
        }

        $questArrays = $this->getDomainAction()->execute($npc);

        $this->assertEquals($npc->getQuestsPerWeek() - 1, $questArrays->count());
    }

    /**
     * @test
     */
    public function it_will_return_an_empty_collection_of_quests_to_join_if_the_week_is_locked()
    {
        $week = factory(Week::class)->states('as-current', 'adventuring-closed')->create();

        $npc = SquadFactory::new()->create();
        $campaignStop = CampaignStopFactory::new()
            ->withCampaign(CampaignFactory::new()->withWeekID($week->id)->withSquadID($npc->id))
            ->create();

        /** @var Continent $fetroya */
        $fetroya = Continent::query()->where('name', '=', Continent::FETROYA)->first();
        $provinces = Province::query()->where('continent_id', '=', $fetroya->id)->inRandomOrder()->get();

        for ($i = 1; $i <= $npc->getQuestsPerWeek() + 1; $i++) {
            $quest = QuestFactory::new()->withProvinceID($provinces->shift()->id)->create();
            for ($j = 1; $j <= $npc->getSideQuestsPerQuest() + 1; $j++) {
                SideQuestFactory::new()->forQuestID($quest->id)->create();
            }
        }

        $questArrays = $this->getDomainAction()->execute($npc);

        $this->assertTrue($questArrays->isEmpty());
    }
}
