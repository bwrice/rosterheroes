<?php


namespace App\Domain\Combat\Events\Handlers;


use App\Domain\Combat\Events\AttackDamagesCombatant;
use App\Domain\Combat\Events\CombatEvent;
use App\Domain\Models\SideQuestEvent;
use Illuminate\Support\Str;

class MinionDamagesHeroHandler extends SideQuestEventHandler
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
            'event_type' => SideQuestEvent::TYPE_MINION_DAMAGES_HERO,
            'moment' => $combatEvent->moment(),
            'data' => $this->getAttackDataArray($combatEvent->getCombatAttack(), $combatEvent->getTarget(), $combatEvent->getAttacker(), $combatEvent->getDamage())
        ]);
    }
}
