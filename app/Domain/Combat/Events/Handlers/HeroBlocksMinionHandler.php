<?php


namespace App\Domain\Combat\Events\Handlers;


use App\Domain\Combat\Events\AttackBlocked;
use App\Domain\Combat\Events\AttackKillsCombatant;
use App\Domain\Combat\Events\CombatEvent;
use App\Domain\Models\SideQuestEvent;

class HeroBlocksMinionHandler extends SideQuestEventHandler
{

    public function streams(): array
    {
        return [
            AttackBlocked::EVENT_STREAM
        ];
    }

    public function handle(CombatEvent $combatEvent)
    {
        $this->sideQuestResult->sideQuestEvents()->create([
            'event_type' => SideQuestEvent::TYPE_HERO_BLOCKS_MINION,
            'moment' => $combatEvent->moment(),
            'data' => $this->getAttackDataArray($combatEvent->getCombatAttack(), $combatEvent->getTarget(), $combatEvent->getAttacker(), null)
        ]);
    }
}
