<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\CombatMinion;
use App\Domain\Combat\HeroCombatAttack;
use App\SideQuestEvent;
use App\SideQuestResult;

class ProcessSideQuestHeroAttack
{
    /**
     * @param SideQuestResult $sideQuestResult
     * @param int $moment
     * @param int $damageReceived
     * @param HeroCombatAttack $heroCombatAttack
     * @param CombatMinion $combatMinion
     * @param $block
     * @return SideQuestEvent
     */
    public function execute(SideQuestResult $sideQuestResult, int $moment, int $damageReceived, HeroCombatAttack $heroCombatAttack, CombatMinion $combatMinion, $block)
    {
        if ($block) {
            $kill = false;
        } else {
            $kill = $combatMinion->getCurrentHealth() <= 0;
        }
        /** @var SideQuestEvent $sideQuestEvent */
        $sideQuestEvent = SideQuestEvent::query()->create([
            'side_quest_result_id' => $sideQuestResult->id,
            'moment' => $moment,
            'data' => [
                'type' => 'hero-attack',
                'damage' => $damageReceived,
                'block' => $block,
                'kill' => $kill,
                'attacker' => [
                    'type' => 'hero',
                    'hero_id' => $heroCombatAttack->getHeroID(),
                    'item_id' => $heroCombatAttack->getItemID()
                ],
                'defender' => [
                    'type' => 'minion',
                    'minion_id' => $combatMinion->getMinionID()
                ]
            ]
        ]);
        return $sideQuestEvent;
    }
}
