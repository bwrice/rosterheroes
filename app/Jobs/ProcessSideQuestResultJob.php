<?php

namespace App\Jobs;

use App\Domain\Actions\Combat\ProcessSideQuestResult;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\SideQuestResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSideQuestResultJob implements ShouldQueue
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
     * @param ProcessSideQuestResult $processSideQuestResult
     * @throws \Exception
     */
    public function handle(ProcessSideQuestResult $processSideQuestResult)
    {
        $processSideQuestResult->execute($this->sideQuestResult);
    }
}
