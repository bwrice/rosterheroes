<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Jobs\ProcessCombatForSideQuestResultJob;
use App\SideQuestResult;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessWeeklySideQuestCombat extends ProcessWeeklySideQuestsAction
{
    protected function getProcessedAtKey(): string
    {
        return 'combat_processed_at';
    }

    protected function getProcessSideQuestResultJob(SideQuestResult $sideQuestResult): ShouldQueue
    {
        return new ProcessCombatForSideQuestResultJob($sideQuestResult);
    }
}
