<?php


namespace App\Domain\Combat\Events\Handlers;


use App\Domain\Combat\Events\CombatEvent;

interface CombatEventHandler
{
    public function streams(): array;

    public function handle(CombatEvent $combatEvent);
}
