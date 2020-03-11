<?php


namespace App\Domain\Collections;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\AbstractCombatant;
use App\Domain\Models\CombatPosition;

class AbstractCombatantCollection extends CombatantCollection
{
    public function updateCombatPositions(CombatPositionCollection $allCombatPositions)
    {
        $survivors = $this->survivors();
        $initialCombatPositions = $survivors->getInitialCombatPositions();
        $combatPositionsWithoutSurvivors = $allCombatPositions->diff($initialCombatPositions);
        if ($combatPositionsWithoutSurvivors->isNotEmpty() && $initialCombatPositions->isNotEmpty()) {
            $closestProximityPosition = $initialCombatPositions->closestProximity();
            $survivors->withInitialCombatPosition($closestProximityPosition)->setInheritedCombatPositions($combatPositionsWithoutSurvivors);
        }
    }

    /**
     * @return CombatPositionCollection
     */
    protected function getInitialCombatPositions()
    {
        return (new CombatPositionCollection($this->map(function (AbstractCombatant $abstractCombatant) {
            return $abstractCombatant->getInitialCombatPosition();
        })))->unique();
    }

    protected function withInitialCombatPosition(CombatPosition $combatPosition)
    {
        return $this->filter(function (AbstractCombatant $abstractCombatant) use ($combatPosition) {
            return $abstractCombatant->getInitialCombatPosition()->id === $combatPosition->id;
        });
    }

    protected function setInheritedCombatPositions(CombatPositionCollection $inheritedCombatPositions)
    {
        return $this->each(function (AbstractCombatant $abstractCombatant) use ($inheritedCombatPositions) {
            $abstractCombatant->setInheritedCombatPositions($inheritedCombatPositions);
        });
    }

    public function getReadyAttacks()
    {
        $combatAttacks = collect();
        $this->each(function (AbstractCombatant $abstractCombatant) use ($combatAttacks) {
            $abstractCombatant->getReadyAttacks()->each(function ($attack) use ($combatAttacks) {
                $combatAttacks->push($attack);
            });
        });
        return $combatAttacks;
    }

    public function getPossibleTargets()
    {
        return $this->filter(function (AbstractCombatant $combatant) {
            return $combatant->getCurrentHealth() > 0;
        });
    }

}
