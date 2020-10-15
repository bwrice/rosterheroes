<?php


namespace App\Domain\Combat\Events\Handlers;


use App\Domain\Combat\Events\AttackDamagesCombatant;
use App\Domain\Combat\Events\AttackKillsCombatant;
use App\Domain\Combat\Events\CombatEvent;
use App\Domain\Models\SideQuestEvent;

class HeroKillsMinionHandler extends SideQuestEventHandler
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
            'event_type' => SideQuestEvent::TYPE_HERO_KILLS_MINION,
            'moment' => $combatEvent->moment(),
            'data' => $this->getAttackDataArray($combatEvent->getCombatAttack(), $combatEvent->getAttacker(), $combatEvent->getTarget(), $combatEvent->getDamage())
        ]);
    }
}
