<?php


namespace App\Domain\Actions\Combat;


use App\Aggregates\SideQuestEventAggregate;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Support\Str;

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

        $uuid = Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);
        $data = [
            'type' => 'hero-attack',
            'damage' => $damageReceived,
            'block' => $block,
            'kill' => $kill,
            'attacker' => [
                'type' => 'hero',
                'hero_uuid' => $heroCombatAttack->getHeroUuid(),
                'item_uuid' => $heroCombatAttack->getItemUuid()
            ],
            'defender' => [
                'type' => 'minion',
                'minion_id' => $combatMinion->getMinionID()
            ]
        ];
        $aggregate->createSideQuestEvent($sideQuestResult->id, $moment, $data)->persist();
        return SideQuestEvent::findUuid($uuid);
    }
}
