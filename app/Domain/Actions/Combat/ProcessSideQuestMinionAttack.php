<?php


namespace App\Domain\Actions\Combat;


use App\Aggregates\SideQuestEventAggregate;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\SideQuestResult;
use Illuminate\Support\Str;

class ProcessSideQuestMinionAttack
{
    /**
     * @param SideQuestResult $sideQuestResult
     * @param int $moment
     * @param int $damageReceived
     * @param CombatMinion $combatMinion
     * @param MinionCombatAttack $minionCombatAttack
     * @param CombatHero $combatHero
     * @param $block
     * @return SideQuestEvent
     */
    public function execute(
        int $moment,
        int $damageReceived,
        CombatMinion $combatMinion,
        MinionCombatAttack $minionCombatAttack,
        CombatHero $combatHero,
        $block)
    {
        $data = [
            'minionCombatAttack' => $minionCombatAttack->toArray(),
            'combatHero' => $combatHero->toArray(),
            'combatMinion' => $combatMinion->toArray()
        ];

        if ($block) {
            $combatHero->addBlock();
            $eventType = SideQuestEvent::TYPE_HERO_BLOCKS_MINION;
        } else {
            $data['damage'] = $damageReceived;
            $combatHero->addDamageReceived($damageReceived);
            if ($combatHero->getCurrentHealth() > 0) {
                $eventType = SideQuestEvent::TYPE_MINION_DAMAGES_HERO;
            } else {
                $eventType = SideQuestEvent::TYPE_MINION_KILLS_HERO;
            }
        }

        return new SideQuestEvent([
            'uuid' => (string) Str::uuid(),
            'event_type' => $eventType,
            'moment' => $moment,
            'data' => $data
        ]);
    }

}
