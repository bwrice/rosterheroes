<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Actions\SetupQuestForNextWeek;
use App\Domain\Models\Quest;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\SetupQuestForNextWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;

class SetupAllQuestsForNextWeek implements FinalizeWeekDomainAction
{

    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $asyncJobs = Quest::all()->map(function (Quest $quest) {
            return new SetupQuestForNextWeekJob($quest);
        })->toArray();

        ChainGroup::create($asyncJobs, [
            new FinalizeWeekJob($finalizeWeekStep + 1)
        ])->dispatch();
    }
}
