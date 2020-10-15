<?php


namespace App\Domain\Combat\Events\Handlers;


use App\Domain\Combat\Events\AttackKillsCombatant;
use App\Domain\Combat\Events\CombatEvent;
use App\Domain\Models\SideQuestEvent;

class MinionKillsHeroHandler extends SideQuestEventHandler
{

    public function streams(): array
    {
        return [
            AttackKillsCombatant::EVENT_STREAM
        ];
    }

    public function handle(CombatEvent $combatEvent)
    {
        $this->sideQuestResult->sideQuestEvents()->create([
            'event_type' => SideQuestEvent::TYPE_MINION_KILLS_HERO,
            'moment' => $combatEvent->moment(),
            'data' => $this->getAttackDataArray($combatEvent->getCombatAttack(), $combatEvent->getTarget(), $combatEvent->getAttacker(), $combatEvent->getDamage())
        ]);
    }
}
