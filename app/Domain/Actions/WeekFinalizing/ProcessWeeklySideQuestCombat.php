<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Facades\CurrentWeek;
use App\Jobs\ProcessCombatForSideQuestResultJob;
use App\SideQuestResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;

class ProcessWeeklySideQuestCombat extends ProcessWeeklySideQuestsAction
{
    protected function getBaseQuery(): Builder
    {
        return SideQuestResult::query()->whereNull('combat_processed_at');
    }

    protected function getProcessSideQuestResultJob(SideQuestResult $sideQuestResult): ShouldQueue
    {
        return new ProcessCombatForSideQuestResultJob($sideQuestResult);
    }

    /**
     * @throws \Exception
     */
    protected function validateReady()
    {
        if (! CurrentWeek::finalizing()) {
            throw new \Exception("Week must be finalizing to process side quest combat");
        }
    }
}
