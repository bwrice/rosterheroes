<?php


namespace App\Domain\Combat;


interface CombatAttack
{
    public function getTargetPosition(): CombatPosition;
}
