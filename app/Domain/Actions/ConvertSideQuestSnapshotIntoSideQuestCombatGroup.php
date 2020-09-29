<?php


namespace App\Domain\Actions;


use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Combat\CombatGroups\SideQuestCombatGroup;
use App\Domain\Models\SideQuestSnapshot;

class ConvertSideQuestSnapshotIntoSideQuestCombatGroup
{
    /**
     * @param SideQuestSnapshot $sideQuestSnapshot
     * @return SideQuestCombatGroup
     */
    public function execute(SideQuestSnapshot $sideQuestSnapshot)
    {
        return new SideQuestCombatGroup(
            $sideQuestSnapshot->uuid,
            new AbstractCombatantCollection()
        );
    }
}
