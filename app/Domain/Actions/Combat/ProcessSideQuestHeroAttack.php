<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\CombatMinion;
use App\Domain\Combat\HeroCombatAttack;
use App\SideQuestEvent;
use App\SideQuestResult;

class ProcessSideQuestHeroAttack
{
    public function execute(SideQuestResult $sideQuestResult, int $moment, int $damageReceived, HeroCombatAttack $heroCombatAttack, CombatMinion $combatMinion, $block)
    {
        if ($block) {

        } else {
            SideQuestEvent::query()->create([
                'side_quest_result_id' => $sideQuestResult->id,
                'moment' => $moment,
                'data' => [
                    'type' => 'attack',
                    'damage' => $damageReceived,
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
        }
    }
}
