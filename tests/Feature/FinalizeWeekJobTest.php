<?php

namespace Tests\Feature;

use App\Domain\Actions\SetupNextWeekAction;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekPlayerGameLogsAction;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekSpiritEnergiesAction;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekFinalStep;
use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestCombat;
use App\Domain\Actions\WeekFinalizing\SetupAllQuestsForNextWeek;
use App\Jobs\FinalizeWeekJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FinalizeWeekJobTest extends TestCase
{
   use DatabaseTransactions;

    /**
     * @param $step
     * @param $domainAction
     * @test
     * @dataProvider provides_it_will_execute_the_expected_finalize_week_action_for_step
     */
    public function it_will_execute_the_expected_finalize_week_action_for_step($step, $domainAction)
    {
        $spy = \Mockery::spy($domainAction);
        app()->instance($domainAction, $spy);
        $job = new FinalizeWeekJob($step);
        $job->handle();
        $spy->shouldHaveReceived('execute')->with($step, []);
    }

    public function provides_it_will_execute_the_expected_finalize_week_action_for_step()
    {
        return [
            'Step 1' => [
                'step' => 1,
                'domainAction' => FinalizeCurrentWeekPlayerGameLogsAction::class
            ],
            'Step 2' => [
                'step' => 2,
                'domainAction' => FinalizeCurrentWeekSpiritEnergiesAction::class
            ],
            'Step 3' => [
                'step' => 3,
                'domainAction' => ProcessWeeklySideQuestCombat::class
            ],
            'Step 4' => [
                'step' => 4,
                'domainAction' => SetupAllQuestsForNextWeek::class
            ],
            'Step 5' => [
                'step' => 5,
                'domainAction' => FinalizeWeekFinalStep::class
            ],
        ];
    }
}
