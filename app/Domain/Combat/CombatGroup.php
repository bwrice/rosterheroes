<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatActionCollection;
use App\Domain\Collections\CombatantCollection;

interface CombatGroup
{
    public function getCombatActions(int $moment): CombatActionCollection;

    public function getCombatantsForPosition(CombatPosition $combatPosition): CombatantCollection;
}
