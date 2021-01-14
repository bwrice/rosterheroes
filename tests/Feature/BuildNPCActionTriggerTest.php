<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\ActionTriggers\NPCActionTrigger;
use App\Domain\Actions\NPC\BuildNPCActionTrigger;
use App\Facades\NPC;
use App\Factories\Models\ChestFactory;
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
        $match = $trigger->getActions()->first(function ($action, $key) {
            return $key === NPCActionTrigger::KEY_OPEN_CHESTS;
        });
        $this->assertNotNull($match);
        /** @var Collection $actionChests */
        $actionChests = $match['chests'];
        $this->assertEquals($count, $actionChests->count());
        $this->assertArrayElementsEqual($chestsTopOpen->pluck('id')->toArray(), $actionChests->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_will_not_include_an_open_chest_trigger_if_npc_has_no_chests_to_open()
    {
        $npc = SquadFactory::new()->create();
        NPC::shouldReceive('isNPC')->andReturn(true);

        $trigger = $this->getDomainAction()->execute($npc, 100);
        $match = $trigger->getActions()->first(function ($action, $key) {
            return $key === NPCActionTrigger::KEY_OPEN_CHESTS;
        });
        $this->assertNull($match);
    }
}
