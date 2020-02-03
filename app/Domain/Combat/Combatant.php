<?php


namespace App\Domain\Combat;


interface Combatant
{
    public function receiveDamage(int $initialDamage): int;

//    public function handleDamageReceived(int $damage);
}
