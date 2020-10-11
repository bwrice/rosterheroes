<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use Illuminate\Support\Collection;

class ExecuteCombatAttack
{
    protected FindTargetsForAttack $findTargetsForAttack;
    protected ExecuteCombatAttackOnCombatant $executeCombatAttackOnCombatant;

    public function __construct(
        FindTargetsForAttack $findTargetsForAttack,
        ExecuteCombatAttackOnCombatant $executeCombatAttackOnCombatant)
    {
        $this->findTargetsForAttack = $findTargetsForAttack;
        $this->executeCombatAttackOnCombatant = $executeCombatAttackOnCombatant;
    }

    public function execute(CombatAttackInterface $combatAttack, Collection $possibleTargets, int $moment)
    {
        $targets = $this->findTargetsForAttack->execute($combatAttack, $possibleTargets);

        $targetsCount = $targets->count();
        $targets->each(function (Combatant $target) use ($combatAttack, $moment, $targetsCount) {
            $this->executeCombatAttackOnCombatant->execute($combatAttack, $target, $moment, $targetsCount);
        });
    }
}
