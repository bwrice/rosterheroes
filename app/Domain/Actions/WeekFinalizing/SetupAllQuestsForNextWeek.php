<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Quest;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\SetupQuestForNextWeekJob;
use Bwrice\LaravelJobChainGroups\Facades\JobChainGroups;

class SetupAllQuestsForNextWeek implements FinalizeWeekDomainAction
{

    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $asyncJobs = Quest::all()->map(function (Quest $quest) {
            return new SetupQuestForNextWeekJob($quest);
        })->toArray();

        JobChainGroups::create($asyncJobs, [
            new FinalizeWeekJob($finalizeWeekStep + 1)
        ])->dispatch();
    }
}
