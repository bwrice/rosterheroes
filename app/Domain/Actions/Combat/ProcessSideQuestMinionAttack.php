<?php


namespace App\Domain\Actions\Combat;


use App\Aggregates\SideQuestEventAggregate;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\SideQuestEvent;
use App\SideQuestResult;
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
        SideQuestResult $sideQuestResult,
        int $moment,
        int $damageReceived,
        CombatMinion $combatMinion,
        MinionCombatAttack $minionCombatAttack,
        CombatHero $combatHero,
        $block)
    {
        $uuid = (string) Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);

        if ($block) {
            $aggregate->createHeroBlocksMinionEvent(
                $sideQuestResult->id,
                $moment,
                [
                    'minionCombatAttack' => $minionCombatAttack->toArray(),
                    'combatHero' => $combatHero->toArray(),
                    'combatMinion' => $combatMinion->toArray()
                ]
            );
        } else {
            if ($combatHero->getCurrentHealth() > 0) {
                $aggregate->createMinionDamagesHeroEvent(
                    $sideQuestResult->id,
                    $moment,
                    [
                        'damage' => $damageReceived,
                        'minionCombatAttack' => $minionCombatAttack->toArray(),
                        'combatHero' => $combatHero->toArray(),
                        'combatMinion' => $combatMinion->toArray()
                    ]
                );
            } else {
                $aggregate->createMinionKillsHeroEvent(
                    $sideQuestResult->id,
                    $moment,
                    [
                        'damage' => $damageReceived,
                        'minionCombatAttack' => $minionCombatAttack->toArray(),
                        'combatHero' => $combatHero->toArray(),
                        'combatMinion' => $combatMinion->toArray()
                    ]
                );
            }
        }

        $aggregate->persist();

        return SideQuestEvent::findUuidOrFail($uuid);
    }

}
