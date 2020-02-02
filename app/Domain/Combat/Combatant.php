<?php


namespace App\Domain\Combat;


interface Combatant
{
    public function calculateActualDamage(int $initialDamage): int;

    public function handleDamageReceived(int $damage);
}
