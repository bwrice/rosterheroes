<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Collections\CombatEventCollection;

interface CombatGroupInterface
{
    public function getCombatActions(int $momentCount): CombatAttackCollection;

    public function receiveAttack(CombatAttackInterface $combatAttack): CombatEventCollection;

    public function isDefeated(CombatMoment $combatMoment): bool;
}
