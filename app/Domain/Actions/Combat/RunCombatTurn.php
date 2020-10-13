<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Combatants\CombatantInterface;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\CombatGroups\CombatGroup;
use Illuminate\Support\Collection;

class RunCombatTurn
{
    /**
     * @var GetReadyAttacksForCombatant
     */
    protected GetReadyAttacksForCombatant $getReadyAttacksForCombatant;
    /**
     * @var ExecuteCombatAttack
     */
    protected ExecuteCombatAttack $executeCombatAttack;

    public function __construct(GetReadyAttacksForCombatant $getReadyAttacksForCombatant, ExecuteCombatAttack $executeCombatAttack)
    {
        $this->getReadyAttacksForCombatant = $getReadyAttacksForCombatant;
        $this->executeCombatAttack = $executeCombatAttack;
    }

    /**
     * @param CombatGroup $attackingGroup
     * @param CombatGroup $targetedGroup
     * @param int $moment
     * @return Collection
     */
    public function execute(CombatGroup $attackingGroup, CombatGroup $targetedGroup, int $moment)
    {
        $attackers = $attackingGroup->getPossibleAttackers($moment);
        $targets = $targetedGroup->getPossibleTargets($moment);
        $combatEvents = collect();
        $attackers->each(function (Combatant $attackingCombatant) use ($attackers, $targets, $moment, &$combatEvents) {

            $readyAttacks = $this->getReadyAttacksForCombatant->execute($attackingCombatant, $attackers);
            $readyAttacks->each(function (CombatAttackInterface $readyAttack) use ($attackingCombatant, $targets, $moment, &$combatEvents) {
                $eventsForAttack = $this->executeCombatAttack->execute($readyAttack, $attackingCombatant, $targets, $moment);
                $combatEvents = $combatEvents->merge($eventsForAttack);
            });
        });
        return $combatEvents;
    }
}
