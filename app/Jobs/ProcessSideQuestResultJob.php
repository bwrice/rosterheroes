<?php

namespace App\Jobs;

use App\Domain\Actions\Combat\ProcessSideQuestResult;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSideQuestResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var SideQuest
     */
    public $sideQuest;
    /**
     * @var CampaignStop
     */
    public $campaignStop;

    /**
     * ProcessSideQuestResultJob constructor.
     * @param CampaignStop $campaignStop
     * @param SideQuest $sideQuest
     */
    public function __construct(CampaignStop $campaignStop, SideQuest $sideQuest)
    {
        $this->campaignStop = $campaignStop;
        $this->sideQuest = $sideQuest;
    }

    /**
     * @param ProcessSideQuestResult $processSideQuestResult
     * @throws \Exception
     */
    public function handle(ProcessSideQuestResult $processSideQuestResult)
    {
        $processSideQuestResult->execute($this->campaignStop, $this->sideQuest);
    }

    /**
     * @return SideQuest
     */
    public function getSideQuest(): SideQuest
    {
        return $this->sideQuest;
    }

    /**
     * @return CampaignStop
     */
    public function getCampaignStop(): CampaignStop
    {
        return $this->campaignStop;
    }
}
