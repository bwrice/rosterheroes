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
     * @param int $initialDamage
     * @return mixed
     */
    public function receiveDamage(int $initialDamage);

    /**
     * @param CombatAttack $combatAttack
     * @return mixed
     */
    public function attackBlocked(CombatAttack $combatAttack);
}
