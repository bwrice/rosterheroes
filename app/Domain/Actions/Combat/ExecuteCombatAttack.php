<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Combatants\CombatantInterface;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use Illuminate\Support\Collection;

class ExecuteCombatAttack
{
    protected FindTargetsForAttack $findTargetsForAttack;
    protected ExecuteCombatAttackOnCombatant $executeCombatAttackOnCombatant;
    protected SpendResourceCosts $spendResourceCosts;

    public function __construct(
        FindTargetsForAttack $findTargetsForAttack,
        ExecuteCombatAttackOnCombatant $executeCombatAttackOnCombatant,
        SpendResourceCosts $spendResourceCosts)
    {
        $this->findTargetsForAttack = $findTargetsForAttack;
        $this->executeCombatAttackOnCombatant = $executeCombatAttackOnCombatant;
        $this->spendResourceCosts = $spendResourceCosts;
    }

    /**
     * @param CombatAttackInterface $combatAttack
     * @param CombatantInterface $attacker;
     * @param Collection $possibleTargets
     * @param int $moment
     * @return Collection
     */
    public function execute(CombatAttackInterface $combatAttack, CombatantInterface $attacker, Collection $possibleTargets, int $moment)
    {
        $targets = $this->findTargetsForAttack->execute($combatAttack, $possibleTargets);

        $targetsCount = $targets->count();
        $combatEvents = collect();

        $targets->each(function (Combatant $target) use ($combatAttack, $attacker, $moment, $targetsCount, $combatEvents) {
            $event = $this->executeCombatAttackOnCombatant->execute($combatAttack, $attacker, $target, $moment, $targetsCount);
            $combatEvents->push($event);
        });

        $combatAttack->getResourceCosts()->each(function (ResourceCost $resourceCost) use ($attacker) {
            $this->spendResourceCosts->execute($attacker, $resourceCost);
        });

        return $combatEvents;
    }
}
