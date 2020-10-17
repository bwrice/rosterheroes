<?php


namespace App\Domain\Combat\Events\Handlers;


use App\Domain\Combat\Events\AttackBlocked;
use App\Domain\Combat\Events\CombatEvent;
use App\Domain\Models\SideQuestEvent;
use Illuminate\Support\Str;

class MinionBlocksHeroHandler extends SideQuestEventHandler
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
            'uuid' => (string) Str::uuid(),
            'event_type' => SideQuestEvent::TYPE_MINION_BLOCKS_HERO,
            'moment' => $combatEvent->moment(),
            'data' => $this->getAttackDataArray($combatEvent->getCombatAttack(), $combatEvent->getAttacker(), $combatEvent->getTarget(), null)
        ]);
    }
}
