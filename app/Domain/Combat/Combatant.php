<?php


namespace App\Domain\Combat;


interface Combatant
{
    /**
     * @param int $initialDamage
     * @return int
     */
    public function calculateDamageToReceive(int $initialDamage): int;

    /**
     * @param int $damage
     * @return mixed
     */
    public function receiveDamage(int $damage);

    /**
     * @param CombatAttack $combatAttack
     * @return mixed
     */
    public function attackBlocked(CombatAttack $combatAttack);
}
