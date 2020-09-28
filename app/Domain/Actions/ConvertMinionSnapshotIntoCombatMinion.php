<?php


namespace App\Domain\Actions;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Models\MinionSnapshot;

class ConvertMinionSnapshotIntoCombatMinion
{
    /**
     * @param MinionSnapshot $minionSnapshot
     * @return CombatMinion
     */
    public function execute(MinionSnapshot $minionSnapshot)
    {
        return new CombatMinion(
            $minionSnapshot->uuid,
            $minionSnapshot->starting_health,
            $minionSnapshot->protection,
            $minionSnapshot->block_chance,
            $minionSnapshot->combat_position_id,
            new CombatAttackCollection()
        );
    }
}
