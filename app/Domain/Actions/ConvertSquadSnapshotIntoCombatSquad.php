<?php


namespace App\Domain\Actions;


use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Models\SquadSnapshot;

class ConvertSquadSnapshotIntoCombatSquad
{
    /**
     * @param SquadSnapshot $squadSnapshot
     * @return CombatSquad
     */
    public function execute(SquadSnapshot $squadSnapshot)
    {
        return new CombatSquad(
            $squadSnapshot->uuid,
            $squadSnapshot->experience,
            $squadSnapshot->squad_rank_id,
            new AbstractCombatantCollection()
        );
    }
}
