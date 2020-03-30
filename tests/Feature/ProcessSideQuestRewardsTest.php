<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestResult;
use App\Domain\Actions\ProcessSideQuestRewards;
use App\Domain\Actions\ProcessSideQuestVictoryRewards;
use App\Factories\Models\SideQuestEventFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessSideQuestRewardsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_execute_victory_rewards_if_it_has_a_victory_event()
    {
        $eventFactory = SideQuestEventFactory::new()->withEventType(SideQuestEvent::TYPE_SIDE_QUEST_VICTORY);
        $sideQuestResult = SideQuestResultFactory::new()->withEvents(collect([
            $eventFactory
        ]))->create();

        $processVictoryMock = \Mockery::mock(ProcessSideQuestVictoryRewards::class)
            ->shouldReceive('execute')
            ->getMock();

        app()->instance(ProcessSideQuestVictoryRewards::class, $processVictoryMock);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);
    }

    /**
     * @test
     */
    public function it_will_not_execute_victory_rewards_if_no_victory_event()
    {
        $eventFactory = SideQuestEventFactory::new()->withEventType(SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT);
        $sideQuestResult = SideQuestResultFactory::new()->withEvents(collect([
            $eventFactory
        ]))->create();

        $processVictoryMock = \Mockery::mock(ProcessSideQuestVictoryRewards::class)
            ->shouldNotReceive('execute')
            ->getMock();

        app()->instance(ProcessSideQuestVictoryRewards::class, $processVictoryMock);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);
    }
}
