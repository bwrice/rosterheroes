<?php


namespace App\Domain\Collections;


use App\Domain\Combat\Combatants\AbstractCombatant;
use App\Domain\Models\CombatPosition;

class AbstractCombatantCollection extends CombatantCollection
{
    public function updateCombatPositions(CombatPositionCollection $allCombatPositions)
    {
        $initialCombatPositions = $this->getInitialCombatPositions();
        $combatPositionsWithoutCombatants = $allCombatPositions->diff($initialCombatPositions);
        if ($combatPositionsWithoutCombatants->isNotEmpty() && $initialCombatPositions->isNotEmpty()) {
            $closestProximityPosition = $initialCombatPositions->closestProximity();
            $this->withInitialCombatPosition($closestProximityPosition)->setInheritedCombatPositions($combatPositionsWithoutCombatants);
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
}
