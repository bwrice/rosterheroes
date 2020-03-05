<?php


namespace App\Domain\Actions;


use App\SideQuestResult;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ProcessSideQuestRewards
{
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->rewards_processed_at) {
            throw new \Exception("Rewards already processed for SideQuestResult");
        }

        DB::transaction(function () use ($sideQuestResult) {
            $sideQuestResult->rewards_processed_at = Date::now();
            $sideQuestResult->save();
        });
    }
}
