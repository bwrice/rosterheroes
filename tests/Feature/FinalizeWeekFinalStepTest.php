<?php

namespace Tests\Feature;

use App\Domain\Actions\SetupNextWeekAction;
use App\Domain\Actions\WeekFinalizing\ClearWeeklyPlayerSpiritsFromHeroes;
use App\Domain\Actions\WeekFinalizing\DispatchProcessSideQuestResultJobs;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekFinalStep;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Jobs\ProcessSideQuestRewardsJob;
use App\Domain\Models\SideQuestResult;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FinalizeWeekFinalStepTest extends TestCase
{
    use DatabaseTransactions;

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
    public function it_will_execute_set_up_next_week_action()
    {
        $setupNextWeekSpy = \Mockery::spy(SetupNextWeekAction::class);

        app()->instance(SetupNextWeekAction::class, $setupNextWeekSpy);

        $nextStep = rand(1,5);
        /** @var FinalizeWeekFinalStep $domainAction */
        $domainAction = app(FinalizeWeekFinalStep::class);
        $domainAction->execute($nextStep);

        $setupNextWeekSpy->shouldHaveReceived('execute');
    }

    /**
     * @test
     */
    public function it_will_execute_clear_weekly_player_spirits()
    {
        $setupNextWeekSpy = \Mockery::spy(ClearWeeklyPlayerSpiritsFromHeroes::class);

        app()->instance(ClearWeeklyPlayerSpiritsFromHeroes::class, $setupNextWeekSpy);

        $nextStep = rand(1,5);
        /** @var FinalizeWeekFinalStep $domainAction */
        $domainAction = app(FinalizeWeekFinalStep::class);
        $domainAction->execute($nextStep);

        $setupNextWeekSpy->shouldHaveReceived('execute');
    }

    /**
     * @test
     */
    public function it_will_execute_dispatch_process_side_quest_results_jobs_action()
    {
        $setupNextWeekSpy = \Mockery::spy(DispatchProcessSideQuestResultJobs::class);

        app()->instance(DispatchProcessSideQuestResultJobs::class, $setupNextWeekSpy);

        $nextStep = rand(1,5);
        /** @var FinalizeWeekFinalStep $domainAction */
        $domainAction = app(FinalizeWeekFinalStep::class);
        $domainAction->execute($nextStep);

        $setupNextWeekSpy->shouldHaveReceived('execute');
    }
}
