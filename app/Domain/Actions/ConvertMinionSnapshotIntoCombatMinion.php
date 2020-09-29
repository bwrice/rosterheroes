<?php


namespace App\Domain\Actions;


use App\Domain\Actions\Combat\ConvertAttackSnapshotToCombatAttack;
use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Models\AttackSnapshot;
use App\Domain\Models\MinionSnapshot;

class ConvertMinionSnapshotIntoCombatMinion
{
    protected ConvertAttackSnapshotToCombatAttack $convertAttackSnapshotToCombatAttack;

    public function __construct(ConvertAttackSnapshotToCombatAttack $convertAttackSnapshotToCombatAttack)
    {
        $this->convertAttackSnapshotToCombatAttack = $convertAttackSnapshotToCombatAttack;
    }

    /**
     * @param MinionSnapshot $minionSnapshot
     * @return CombatMinion
     */
    public function execute(MinionSnapshot $minionSnapshot)
    {
        $combatAttacks = new CombatAttackCollection();
        $minionSnapshot->attackSnapshots->each(function (AttackSnapshot $attackSnapshot) use ($combatAttacks) {
            $combatAttacks->push($this->convertAttackSnapshotToCombatAttack->execute($attackSnapshot));
        });

        return new CombatMinion(
            $minionSnapshot->uuid,
            $minionSnapshot->starting_health,
            $minionSnapshot->protection,
            $minionSnapshot->block_chance,
            $minionSnapshot->combat_position_id,
            $combatAttacks
        );
    }
}
