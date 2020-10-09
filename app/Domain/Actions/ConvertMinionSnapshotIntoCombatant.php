<?php


namespace App\Domain\Actions;


use App\Domain\Actions\Combat\ConvertAttackSnapshotToCombatAttack;
use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Models\AttackSnapshot;
use App\Domain\Models\MinionSnapshot;

class ConvertMinionSnapshotIntoCombatant
{
    protected ConvertAttackSnapshotToCombatAttack $convertAttackSnapshotToCombatAttack;

    public function __construct(ConvertAttackSnapshotToCombatAttack $convertAttackSnapshotToCombatAttack)
    {
        $this->convertAttackSnapshotToCombatAttack = $convertAttackSnapshotToCombatAttack;
    }

    /**
     * @param MinionSnapshot $minionSnapshot
     * @return Combatant
     */
    public function execute(MinionSnapshot $minionSnapshot)
    {
        $combatAttacks = new CombatAttackCollection();
        $minionSnapshot->attackSnapshots->each(function (AttackSnapshot $attackSnapshot) use ($combatAttacks) {
            $combatAttacks->push($this->convertAttackSnapshotToCombatAttack->execute($attackSnapshot));
        });

        return new Combatant(
            $minionSnapshot->uuid,
            $minionSnapshot->starting_health,
            $minionSnapshot->starting_stamina,
            $minionSnapshot->starting_mana,
            $minionSnapshot->protection,
            $minionSnapshot->block_chance,
            $minionSnapshot->combat_position_id,
            $combatAttacks
        );
    }
}
