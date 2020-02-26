<?php


namespace App\Domain\Actions\Combat;


use App\Aggregates\SideQuestEventAggregate;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Support\Str;

class ProcessSideQuestMinionAttack
{
    /**
     * @param SideQuestResult $sideQuestResult
     * @param int $moment
     * @param int $damageReceived
     * @param MinionCombatAttack $minionCombatAttack
     * @param CombatHero $combatHero
     * @param $block
     * @return SideQuestEvent
     */
    public function execute(SideQuestResult $sideQuestResult, int $moment, int $damageReceived, MinionCombatAttack $minionCombatAttack, CombatHero $combatHero, $block)
    {
        $uuid = (string) Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);

        if ($block) {
            $aggregate->createHeroBlocksMinionEvent(
                $sideQuestResult->id,
                $moment,
                $minionCombatAttack->getMinionUuid(),
                $minionCombatAttack->getCombatAttack()->getAttackUuid(),
                $combatHero->getHeroUuid(),
            );
        } else {
            if ($combatHero->getCurrentHealth() > 0) {
                $aggregate->createMinionDamagesHeroEvent(
                    $sideQuestResult->id,
                    $moment,
                    $minionCombatAttack->getMinionUuid(),
                    $minionCombatAttack->getCombatAttack()->getAttackUuid(),
                    $combatHero->getHeroUuid(),
                    $damageReceived
                );
            } else {
                $aggregate->createMinionKillsHeroEvent(
                    $sideQuestResult->id,
                    $moment,
                    $minionCombatAttack->getMinionUuid(),
                    $minionCombatAttack->getCombatAttack()->getAttackUuid(),
                    $combatHero->getHeroUuid(),
                    $damageReceived
                );
            }
        }

        $aggregate->persist();

        return SideQuestEvent::findUuidOrFail($uuid);
    }

}
