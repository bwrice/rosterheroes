<?php


namespace App\Domain;


use App\SideQuestResult;
use Illuminate\Support\Facades\Date;

class ProcessSideQuestResultSideEffects
{
    /**
     * @param SideQuestResult $sideQuestResult
     * @throws \Exception
     */
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->side_effects_processed_at) {
            throw new \Exception("Side effects already processed for side quest result: " . $sideQuestResult->id);
        }

        if (! $sideQuestResult->combat_processed_at) {
            throw new \Exception("Cannot process side effects because combat not processed for side quest result: " . $sideQuestResult->id);
        }

        $sideQuestResult->side_effects_processed_at = Date::now();
        $sideQuestResult->save();
    }
}
