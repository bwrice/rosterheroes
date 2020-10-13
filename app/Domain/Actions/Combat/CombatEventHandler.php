<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Events\CombatEvent;

interface CombatEventHandler
{
    public function streams(): array;

    public function handle(CombatEvent $combatEvent);
}
