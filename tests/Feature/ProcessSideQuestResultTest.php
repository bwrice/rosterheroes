<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestResult;
use App\Domain\Actions\Combat\RunCombatTurn;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\SideQuestGroupFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
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
    }
}
