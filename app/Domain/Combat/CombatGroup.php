<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatAttacksCollection;
use App\Domain\Collections\CombatEventCollection;

interface CombatGroup
{
    public function getCombatActions(int $momentCount): CombatAttacksCollection;

    public function receiveAttack(CombatAttack $combatAttack): CombatEventCollection;

    public function isDefeated(CombatMoment $combatMoment): bool;
}
