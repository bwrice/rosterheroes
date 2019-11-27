<?php


namespace App\Domain\Combat;


interface CombatEventHandler
{
    public function handleCombatEvent(CombatEvent $combatEvent, CombatMoment $combatMoment);
}
