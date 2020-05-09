<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\SideQuestEvent;
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
     * @param int $moment
     * @param int $damageReceived
     * @param CombatHero $combatHero
     * @param HeroCombatAttack $heroCombatAttack
     * @param CombatMinion $combatMinion
     * @param $block
     * @return SideQuestEvent
     */
    public function execute(
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

        $data = [
            'combatHero' => $combatHero->toArray(),
            'heroCombatAttack' => $heroCombatAttack->toArray(),
            'combatMinion' => $combatMinion->toArray(),
            'staminaCost' => $staminaCost,
            'manaCost' => $manaCost
        ];

        if ($block) {
            $eventType = SideQuestEvent::TYPE_MINION_BLOCKS_HERO;
        } else {
            $combatHero->addDamageDealt($damageReceived);
            $heroCombatAttack->addDamageDealt($damageReceived);
            $data['damage'] = $damageReceived;

            if ($combatMinion->getCurrentHealth() > 0) {
                $eventType = SideQuestEvent::TYPE_HERO_DAMAGES_MINION;
            } else {
                $combatHero->addMinionKill();
                $heroCombatAttack->addMinionKill();
                $eventType = SideQuestEvent::TYPE_HERO_KILLS_MINION;
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
