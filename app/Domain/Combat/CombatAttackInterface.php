<?php


namespace App\Domain\Combat;


interface CombatAttackInterface
{
    public function getTargetPosition(): CombatPosition;
}
