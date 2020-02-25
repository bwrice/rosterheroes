<?php


namespace App\Domain\Actions\Combat;


use App\Aggregates\SideQuestEventAggregate;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\Events\HeroAttackOnMinion;
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
    public function execute(
        SideQuestResult $sideQuestResult,
        int $moment,
        int $damageReceived,
        HeroCombatAttack $heroCombatAttack,
        CombatMinion $combatMinion,
        $block)
    {
        $uuid = Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);

        if ($block) {
            $aggregate->createMinionBlocksHeroEvent(
                $sideQuestResult->id,
                $moment,
                $heroCombatAttack->getHeroUuid(),
                $heroCombatAttack->getCombatAttack()->getAttackUuid(),
                $heroCombatAttack->getItemUuid(),
                $combatMinion->getMinionUuid(),
            );
        } else {
            if ($combatMinion->getCurrentHealth() > 0) {

                $aggregate->createHeroDamagesMinionEvent(
                    $sideQuestResult->id,
                    $moment,
                    $heroCombatAttack->getHeroUuid(),
                    $heroCombatAttack->getCombatAttack()->getAttackUuid(),
                    $heroCombatAttack->getItemUuid(),
                    $combatMinion->getMinionUuid(),
                    $damageReceived
                );
            } else {
                $aggregate->createHeroKillsMinionEvent(
                    $sideQuestResult->id,
                    $moment,
                    $heroCombatAttack->getHeroUuid(),
                    $heroCombatAttack->getCombatAttack()->getAttackUuid(),
                    $heroCombatAttack->getItemUuid(),
                    $combatMinion->getMinionUuid(),
                    $damageReceived
                );
            }
        }
        $aggregate->persist();
        return SideQuestEvent::findUuid($uuid);
    }
}
