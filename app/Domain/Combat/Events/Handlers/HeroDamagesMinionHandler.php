<?php


namespace App\Domain\Combat\Events\Handlers;


use App\Domain\Combat\Events\AttackDamagesCombatant;
use App\Domain\Combat\Events\CombatEvent;
use App\Domain\Models\SideQuestEvent;
use Illuminate\Support\Str;

class HeroDamagesMinionHandler extends SideQuestEventHandler
{

    public function streams(): array
    {
        return [
            AttackDamagesCombatant::EVENT_STREAM
        ];
    }

    public function handle(CombatEvent $combatEvent)
    {
        $this->sideQuestResult->sideQuestEvents()->create([
            'uuid' => (string) Str::uuid(),
            'event_type' => SideQuestEvent::TYPE_HERO_DAMAGES_MINION,
            'moment' => $combatEvent->moment(),
            'data' => $this->getAttackDataArray($combatEvent->getCombatAttack(), $combatEvent->getAttacker(), $combatEvent->getTarget(), $combatEvent->getDamage())
        ]);
    }
}
