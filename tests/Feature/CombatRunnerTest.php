<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\CombatEventHandler;
use App\Domain\Actions\Combat\RunCombatTurn;
use App\Domain\Combat\CombatGroups\CombatGroup;
use App\Domain\Combat\CombatRunner;
use App\Domain\Combat\Events\CombatEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CombatRunnerTest extends TestCase
{
    /**
     * @return CombatRunner
     */
    protected function getDomainAction()
    {
        return app(CombatRunner::class);
    }

    /**
     * @test
     */
    public function it_will_return_side_A_victory_if_side_B_is_defeated()
    {
        $sideAMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(false)
            ->getMock();
        $sideBMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(true)
            ->getMock();

        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')
            ->andReturn(collect())
            ->getMock();

        $this->instance(RunCombatTurn::class, $runCombatTurnMock);

        $result = $this->getDomainAction()->execute($sideAMock, $sideBMock);
        $this->assertEquals(CombatRunner::SIDE_A, $result['victorious_side']);
    }

    /**
     * @test
     */
    public function it_will_return_side_B_victory_if_side_A_is_defeated()
    {
        $sideAMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(true)
            ->getMock();
        $sideBMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(false)
            ->getMock();

        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')
            ->andReturn(collect())
            ->getMock();

        $this->instance(RunCombatTurn::class, $runCombatTurnMock);

        $result = $this->getDomainAction()->execute($sideAMock, $sideBMock);
        $this->assertEquals(CombatRunner::SIDE_B, $result['victorious_side']);
    }

    /**
     * @test
     */
    public function it_will_return_NULL_victorious_side_if_max_moments_reached()
    {
        $sideAMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(false)
            ->getMock();
        $sideBMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(false)
            ->getMock();

        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')
            ->andReturn(collect())
            ->getMock();

        $this->instance(RunCombatTurn::class, $runCombatTurnMock);

        $maxMoments = rand(5, 10);

        $result = $this->getDomainAction()->execute($sideAMock, $sideBMock, $maxMoments);
        $this->assertNull($result['victorious_side']);
        $this->assertEquals($maxMoments, $result['moment']);
    }

    /**
     * @test
     */
    public function it_will_execute_run_combat_turn_for_each_side_for_each_moment()
    {
        $sideAMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(false)
            ->getMock();

        $sideBMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(false)
            ->getMock();

        $mock = $this->getMockBuilder(RunCombatTurn::class)
            ->disableOriginalConstructor()
            ->getMock();

        $maxMoments = rand(2, 5);
        $mock->expects($this->exactly($maxMoments * 2))
            ->method('execute')
            ->willReturn(collect());

        $this->instance(RunCombatTurn::class, $mock);

        $this->getDomainAction()->execute($sideAMock, $sideBMock, $maxMoments);
    }

    /**
     * @test
     */
    public function it_will_execute_event_handlers_for_their_turns_and_streams()
    {
        $sideAMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(false)
            ->getMock();

        $sideBMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('isDefeated')
            ->andReturn(false)
            ->getMock();

        $streamOneEvent = \Mockery::mock(CombatEvent::class)
            ->shouldReceive('eventStream')
            ->andReturn('Stream 1')
            ->getMock();
        $streamTwoEvent = \Mockery::mock(CombatEvent::class)
            ->shouldReceive('eventStream')
            ->andReturn('Stream 2')
            ->getMock();
        $streamThreeEvent = \Mockery::mock(CombatEvent::class)
            ->shouldReceive('eventStream')
            ->andReturn('Stream 3')
            ->getMock();
        $streamFourEvent = \Mockery::mock(CombatEvent::class)
            ->shouldReceive('eventStream')
            ->andReturn('Stream 4')
            ->getMock();

        $runCombatTurnMock = $this->getMockBuilder(RunCombatTurn::class)
            ->disableOriginalConstructor()
            ->getMock();

        $returnOne = collect([
            $streamOneEvent
        ]);
        $returnTwo = collect([
            $streamFourEvent
        ]);
        $returnThree = collect([
            $streamTwoEvent,
            $streamThreeEvent
        ]);

        $runCombatTurnMock->method('execute')->willReturn($returnOne, $returnTwo, $returnThree, collect([]));
        $this->instance(RunCombatTurn::class, $runCombatTurnMock);

        $handlerAOne = \Mockery::mock(CombatEventHandler::class)
            ->shouldReceive('streams')
            ->andReturn([
                'Stream 1',
                'Stream 3'
            ])->getMock();
        $handlerAOne->shouldReceive('handle')->twice();

        $handlerATwo = \Mockery::mock(CombatEventHandler::class)
            ->shouldReceive('streams')
            ->andReturn([
                'Stream 2'
            ])->getMock();
        $handlerATwo->shouldReceive('handle')->once();

        $handlerBOne = \Mockery::mock(CombatEventHandler::class)
            ->shouldReceive('streams')
            ->andReturn([
                'Stream 4'
            ])->getMock();
        $handlerBOne->shouldReceive('handle')->once();

        $this->getDomainAction()
            ->registerTurnAHandler($handlerAOne)
            ->registerTurnAHandler($handlerATwo)
            ->registerTurnBHandler($handlerBOne)
            ->execute($sideAMock, $sideBMock, 2);
    }
}
