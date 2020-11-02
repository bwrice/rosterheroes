<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Combatants\CombatantInterface;
use App\Domain\Combat\Events\CombatantAttacks;
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

        // We always add the combatant attacks event
        $combatEvents->push(new CombatantAttacks($attacker, $combatAttack, $targets, $moment));

        // Cycle through targets and add any individual events like attack kills combatant, attack damages combatant, etc.
        $targets->each(function (Combatant $target) use ($combatAttack, $attacker, $moment, $targetsCount, &$combatEvents) {
            $events = $this->executeCombatAttackOnCombatant->execute($combatAttack, $attacker, $target, $moment, $targetsCount);
            $combatEvents = $combatEvents->merge($events);
        });

        $combatAttack->getResourceCosts()->each(function (ResourceCost $resourceCost) use ($attacker) {
            $this->spendResourceCosts->execute($attacker, $resourceCost);
        });

        return $combatEvents;
    }
}
