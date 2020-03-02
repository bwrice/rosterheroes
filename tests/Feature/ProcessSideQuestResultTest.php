<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatSquad;
use App\Domain\Actions\Combat\BuildSideQuestGroup;
use App\Domain\Actions\Combat\ProcessSideQuestResult;
use App\Domain\Actions\Combat\RunCombatTurn;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\SideQuestGroupFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use App\SideQuestEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessSideQuestResultTest extends TestCase
{
    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
    }

    /**
     * @test
     */
    public function it_will_create_side_result_with_a_battlefield_set_event()
    {
        $squad = SquadFactory::new()->create();
        $sideQuest = SideQuestFactory::new()->create();

        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $domainAction->setMaxMoments(5);
        $sideQuestResult = $domainAction->execute($squad, $sideQuest);
        $this->assertEquals($squad->id, $sideQuestResult->squad_id);
        $this->assertEquals($sideQuest->id, $sideQuestResult->side_quest_id);
        $this->assertEquals(CurrentWeek::id(), $sideQuestResult->week_id);
        $this->assertNull($sideQuestResult->rewards_processed_at);
        $sideQuestEvents = $sideQuestResult->sideQuestEvents()
            ->where('event_type', '=', SideQuestEvent::TYPE_BATTLEGROUND_SET)->get();
        $this->assertEquals(1, $sideQuestEvents->count());
    }

    /**
     * @test
     */
    public function it_will_create_a_side_quest_defeat_event_if_the_squad_is_defeated()
    {
        $squad = SquadFactory::new()->create();
        $sideQuest = SideQuestFactory::new()->create();

        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        $combatSquad = CombatSquadFactory::new()->create();
        $combatSquadMock = \Mockery::mock($combatSquad, [
            'isDefeated' => true
        ])->makePartial();

        $buildCombatSquadMock = \Mockery::mock(BuildCombatSquad::class)
            ->shouldReceive('execute')
            ->andReturn($combatSquadMock)->getMock();

        app()->instance(BuildCombatSquad::class, $buildCombatSquadMock);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($squad, $sideQuest);

        $defeatEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT)->first();
        $this->assertNotNull($defeatEvent);
    }

    /**
     * @test
     */
    public function it_will_create_a_side_quest_victory_event_if_the_side_quest_group_is_defeated()
    {
        $squad = SquadFactory::new()->create();
        $sideQuest = SideQuestFactory::new()->create();

        // mock RunCombatTurn
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        // mock CombatSquad
        $combatSquad = CombatSquadFactory::new()->create();
        $combatSquadMock = \Mockery::mock($combatSquad, [
            'isDefeated' => false
        ])->makePartial();

        $buildCombatSquadMock = \Mockery::mock(BuildCombatSquad::class)
            ->shouldReceive('execute')
            ->andReturn($combatSquadMock)->getMock();

        app()->instance(BuildCombatSquad::class, $buildCombatSquadMock);

        // mock SideQuestGroup
        $sideQuestGroup = SideQuestGroupFactory::new()->create();
        $sideQuestGroupMock = \Mockery::mock($sideQuestGroup, [
            'isDefeated' => true
        ])->makePartial();

        $buildSideQuestGroupMock = \Mockery::mock(BuildSideQuestGroup::class)
            ->shouldReceive('execute')
            ->andReturn($sideQuestGroupMock)->getMock();

        app()->instance(BuildSideQuestGroup::class, $buildSideQuestGroupMock);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($squad, $sideQuest);

        $victoryEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_VICTORY)->first();
        $this->assertNotNull($victoryEvent);
    }

}
