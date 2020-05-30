<?php

namespace App\Jobs;

use App\Domain\Actions\Combat\ProcessCombatForSideQuestResult;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCombatForSideQuestResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SideQuestResult
     */
    public $sideQuestResult;

    /**
     * ProcessSideQuestResultJob constructor.
     * @param SideQuestResult $sideQuestResult
     */
    public function __construct(SideQuestResult $sideQuestResult)
    {
        $this->sideQuestResult = $sideQuestResult;
    }

    /**
     * @param ProcessCombatForSideQuestResult $processSideQuestResult
     * @throws \Throwable
     */
    public function handle(ProcessCombatForSideQuestResult $processSideQuestResult)
    {
        $processSideQuestResult->execute($this->sideQuestResult);
    }
}
