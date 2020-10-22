<?php


namespace App\Domain\Actions;


use App\Domain\Combat\CombatGroups\SideQuestCombatGroup;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\SideQuestSnapshot;

class ConvertSideQuestSnapshotIntoSideQuestCombatGroup
{
    protected ConvertMinionSnapshotIntoCombatant $convertMinionSnapshotIntoCombatMinion;

    public function __construct(ConvertMinionSnapshotIntoCombatant $convertMinionSnapshotIntoCombatMinion)
    {
        $this->convertMinionSnapshotIntoCombatMinion = $convertMinionSnapshotIntoCombatMinion;
    }

    /**
     * @param SideQuestSnapshot $sideQuestSnapshot
     * @return SideQuestCombatGroup
     */
    public function execute(SideQuestSnapshot $sideQuestSnapshot)
    {
        $combatMinions = collect();
        $sideQuestSnapshot->minionSnapshots->each(function (MinionSnapshot $minionSnapshot) use ($combatMinions) {
            for ($i = 1; $i <= $minionSnapshot->pivot->count; $i++) {
                $combatMinions->push($this->convertMinionSnapshotIntoCombatMinion->execute($minionSnapshot));
            }
        });
        return new SideQuestCombatGroup(
            $sideQuestSnapshot->uuid,
            $combatMinions
        );
    }
}
