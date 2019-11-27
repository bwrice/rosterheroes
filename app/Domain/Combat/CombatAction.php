<?php


namespace App\Domain\Combat;


interface CombatAction
{
    public function getTargetPosition(): CombatPosition;
}
