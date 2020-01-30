<?php

namespace Tests\Feature;

use App\Domain\Actions\SetupNextWeekAction;
use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekMinionSnapshotsAction;
use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekSquadSnapshotsAction;
use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekTitanSnapshotsAction;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekPlayerGameLogsAction;
use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekSpiritEnergiesAction;
use App\Domain\Actions\WeekFinalizing\ProcessCurrentWeekSkirmishesAction;
use App\Jobs\FinalizeWeekJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FinalizeWeekJobTest extends TestCase
{
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
        $spy->shouldHaveReceived('execute')->with($step);
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
                'domainAction' => BuildCurrentWeekSquadSnapshotsAction::class
            ],
            'Step 4' => [
                'step' => 4,
                'domainAction' => BuildCurrentWeekMinionSnapshotsAction::class
            ],
            'Step 5' => [
                'step' => 5,
                'domainAction' => BuildCurrentWeekTitanSnapshotsAction::class
            ],
            'Step 6' => [
                'step' => 6,
                'domainAction' => ProcessCurrentWeekSkirmishesAction::class
            ],
            'Step 7' => [
                'step' => 7,
                'domainAction' => SetupNextWeekAction::class
            ],
        ];
    }
}
