<?php

namespace App\Jobs;

use App\Domain\ProcessSideQuestResultSideEffects;
use App\SideQuestResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSideQuestSideEffectsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SideQuestResult
     */
    public $sideQuestResult;

    /**
     * ProcessSideQuestRewardsJob constructor.
     * @param SideQuestResult $sideQuestResult
     */
    public function __construct(SideQuestResult $sideQuestResult)
    {
        $this->sideQuestResult = $sideQuestResult;
    }

    /**
     * @param ProcessSideQuestResultSideEffects $processSideEffects
     * @throws \Throwable
     */
    public function handle(ProcessSideQuestResultSideEffects $processSideEffects)
    {
        $processSideEffects->execute($this->sideQuestResult);
    }
}
