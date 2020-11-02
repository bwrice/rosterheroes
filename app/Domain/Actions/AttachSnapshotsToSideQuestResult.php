<?php


namespace App\Domain\Actions;


use App\Domain\Models\SideQuestResult;
use App\Domain\Models\SideQuestSnapshot;
use App\Domain\Models\SquadSnapshot;

class AttachSnapshotsToSideQuestResult
{
    public const EXCEPTION_SQUAD_SNAPSHOT_ALREADY_ATTACHED = 1;
    public const EXCEPTION_SIDE_QUEST_SNAPSHOT_ALREADY_ATTACHED = 2;
    public const EXCEPTION_SQUAD_SNAPSHOT_NOT_FOUND = 3;
    public const EXCEPTION_SIDE_QUEST_SNAPSHOT_NOT_FOUND = 4;

    /**
     * @param SideQuestResult $sideQuestResult
     * @return SideQuestResult
     * @throws \Exception
     */
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->squad_snapshot_id) {
            $message = "Squad snapshot already attached to side-quest result";
            throw new \Exception($message, self::EXCEPTION_SQUAD_SNAPSHOT_ALREADY_ATTACHED);
        }
        if ($sideQuestResult->side_quest_snapshot_id) {
            $message = "Side-quest snapshot already attached to side-quest result";
            throw new \Exception($message, self::EXCEPTION_SIDE_QUEST_SNAPSHOT_ALREADY_ATTACHED);
        }

        $campaign = $sideQuestResult->campaignStop->campaign;

        $squadSnapshot = SquadSnapshot::query()
            ->where('squad_id', '=', $campaign->squad_id)
            ->where('week_id', '=', $campaign->week_id)
            ->first();

        if (is_null($squadSnapshot)) {
            throw new \Exception("Squad snapshot not found", self::EXCEPTION_SQUAD_SNAPSHOT_NOT_FOUND);
        }

        $sideQuestSnapshot = SideQuestSnapshot::query()
            ->where('side_quest_id', '=', $sideQuestResult->side_quest_id)
            ->where('week_id', '=', $campaign->week_id)
            ->first();

        if (is_null($sideQuestSnapshot)) {
            throw new \Exception("Side-quest snapshot not found", self::EXCEPTION_SIDE_QUEST_SNAPSHOT_NOT_FOUND);
        }

        $sideQuestResult->squad_snapshot_id = $squadSnapshot->id;
        $sideQuestResult->side_quest_snapshot_id = $sideQuestSnapshot->id;
        $sideQuestResult->save();
        return $sideQuestResult;
    }
}
