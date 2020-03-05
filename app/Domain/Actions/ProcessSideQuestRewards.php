<?php


namespace App\Domain\Actions;


use App\SideQuestResult;

class ProcessSideQuestRewards
{
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->rewards_processed_at) {
            throw new \Exception("Rewards already processed for SideQuestResult");
        }
    }
}
