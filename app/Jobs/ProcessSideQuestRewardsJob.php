<?php

namespace App\Jobs;

use App\Domain\Actions\ProcessSideQuestRewards;
use App\Domain\Models\SideQuestResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSideQuestRewardsJob implements ShouldQueue
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
     * @param ProcessSideQuestRewards $processSideQuestRewards
     * @throws \Exception
     */
    public function handle(ProcessSideQuestRewards $processSideQuestRewards)
    {
        $processSideQuestRewards->execute($this->sideQuestResult);
    }
}
