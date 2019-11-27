<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatActionCollection;

interface CombatGroup
{
    public function getCombatActions(int $moment): CombatActionCollection;
}
