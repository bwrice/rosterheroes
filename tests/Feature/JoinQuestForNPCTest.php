<?php

namespace Tests\Feature;

use App\Domain\Actions\JoinQuestAction;
use App\Domain\Actions\NPC\JoinQuestForNPC;
use App\Domain\Actions\NPC\NPCAction;
use App\Domain\Models\Squad;
use App\Facades\NPC;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JoinQuestForNPCTest extends NPCActionTest
{
    /**
     * @test
     */
    public function it_will_execute_join_quest_action_for_the_npc_squad()
    {
        $squad = SquadFactory::new()->create();
        NPC::shouldReceive('isNPC')->andReturn(true);
        $quest = QuestFactory::new()->create();

        $mock = \Mockery::mock(JoinQuestAction::class)
            ->shouldReceive('execute')
            ->with($squad, $quest)
            ->getMock();

        $this->app->instance(JoinQuestAction::class, $mock);

        $this->getDomainAction()->execute($squad, $quest);
    }

    /**
     * @return NPCAction
     */
    protected function getDomainAction()
    {
        return app(JoinQuestForNPC::class);
    }
}
