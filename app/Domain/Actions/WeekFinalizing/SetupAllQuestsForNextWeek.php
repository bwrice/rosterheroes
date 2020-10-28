<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Quest;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\SetupQuestForNextWeekJob;
use Illuminate\Support\Facades\Bus;

class SetupAllQuestsForNextWeek implements FinalizeWeekDomainAction
{

    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     * @throws \Throwable
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $jobs = Quest::all()->map(function (Quest $quest) {
            return new SetupQuestForNextWeekJob($quest);
        })->toArray();

        Bus::batch($jobs)->then(function () use ($finalizeWeekStep) {
            FinalizeWeekJob::dispatch($finalizeWeekStep + 1);
        })->dispatch();
    }
}
