<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\ActionTriggers\NPCActionTrigger;
use App\Domain\Actions\NPC\BuildNPCActionTrigger;
use App\Domain\Models\Continent;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Facades\NPC;
use App\Factories\Models\ChestFactory;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BuildNPCActionTriggerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuildNPCActionTrigger
     */
    protected function getDomainAction()
    {
        return app(BuildNPCActionTrigger::class);
    }

    /**
     * @test
     */
    public function it_will_include_a_trigger_to_open_chests_if_an_npc_has_unopened_chests()
    {
        $npc = SquadFactory::new()->create();
        $chestFactory = ChestFactory::new()->withSquadID($npc->id);
        $count = rand(2,5);
        $chestsTopOpen = collect();
        for ($i = 1; $i <= $count; $i++) {
            $chestsTopOpen->push($chestFactory->create());
        }
        NPC::shouldReceive('isNPC')->andReturn(true);

        $trigger = $this->getDomainAction()->execute($npc, 100);

        /** @var Collection $data */
        $data = $trigger->getActions()->get(NPCActionTrigger::KEY_OPEN_CHESTS);
        $this->assertNotNull($data);
        $this->assertEquals($count, $data->count());
        $this->assertArrayElementsEqual($chestsTopOpen->pluck('id')->toArray(), $data->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_will_not_include_an_open_chest_trigger_if_npc_has_no_chests_to_open()
    {
        $npc = SquadFactory::new()->create();
        NPC::shouldReceive('isNPC')->andReturn(true);

        $trigger = $this->getDomainAction()->execute($npc, 100);
        $data = $trigger->getActions()->get(NPCActionTrigger::KEY_OPEN_CHESTS);
        $this->assertNull($data);
    }

    /**
     * @test
     */
    public function it_will_include_quests_and_side_quests_to_join_for_an_npc_without_quests_joined()
    {
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

        NPC::shouldReceive('isNPC')->andReturn(true);

        $trigger = $this->getDomainAction()->execute($npc, 100);

        /** @var Collection $data */
        $data = $trigger->getActions()->get(NPCActionTrigger::KEY_JOIN_QUESTS);
        $this->assertNotNull($data);
        $this->assertEquals($npc->getQuestsPerWeek(), $data->count());


        $data->each(function ($questToJoinArray) use ($fetroya, $npc) {
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
}
