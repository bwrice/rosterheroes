<?php


namespace App\Domain\Actions\Combat;


use App\Aggregates\SideQuestEventAggregate;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Support\Str;

class ProcessSideQuestHeroAttack
{
    /**
     * @var SpendResourceCosts
     */
    private $spendResourceCosts;

    public function __construct(SpendResourceCosts $spendResourceCosts)
    {
        $this->spendResourceCosts = $spendResourceCosts;
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @param int $moment
     * @param int $damageReceived
     * @param CombatHero $combatHero
     * @param HeroCombatAttack $heroCombatAttack
     * @param CombatMinion $combatMinion
     * @param $block
     * @return SideQuestEvent
     */
    public function execute(
        SideQuestResult $sideQuestResult,
        int $moment,
        int $damageReceived,
        CombatHero $combatHero,
        HeroCombatAttack $heroCombatAttack,
        CombatMinion $combatMinion,
        $block)
    {
        $resourceCostResultsCollection = $heroCombatAttack->getResourceCosts()->map(function (ResourceCost $resourceCost) use ($combatHero) {
            return $this->spendResourceCosts->execute($combatHero, $resourceCost);
        });

        $staminaCost = $resourceCostResultsCollection->sum(function ($resultsArray) {
            return $resultsArray['stamina_cost'];
        });
        $manaCost = $resourceCostResultsCollection->sum(function ($resultsArray) {
            return $resultsArray['mana_cost'];
        });

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
                $staminaCost,
                $manaCost
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
                    $damageReceived,
                    $staminaCost,
                    $manaCost
                );
            } else {
                $aggregate->createHeroKillsMinionEvent(
                    $sideQuestResult->id,
                    $moment,
                    $heroCombatAttack->getHeroUuid(),
                    $heroCombatAttack->getCombatAttack()->getAttackUuid(),
                    $heroCombatAttack->getItemUuid(),
                    $combatMinion->getMinionUuid(),
                    $damageReceived,
                    $staminaCost,
                    $manaCost
                );
            }
        }
        $aggregate->persist();
        return SideQuestEvent::findUuid($uuid);
    }
}
