<?php


namespace App\Domain\Collections;


use App\Domain\Combat\CombatHero;
use App\Domain\Models\CombatPosition;

class CombatHeroCollection extends CombatantCollection
{
    public function updateCombatPositions(CombatPositionCollection $allCombatPositions)
    {
        $heroCombatPositions = $this->getInitialCombatPositions();
        $combatPositionsWithoutHeroes = $allCombatPositions->diff($heroCombatPositions);
        if ($combatPositionsWithoutHeroes->isNotEmpty() && $heroCombatPositions->isNotEmpty()) {
            $closestProximityPosition = $heroCombatPositions->closestProximity();
            $this->withInitialCombatPosition($closestProximityPosition)->setInheritedCombatPositions($combatPositionsWithoutHeroes);
        }
    }

    /**
     * @return CombatPositionCollection
     */
    protected function getInitialCombatPositions()
    {
        return (new CombatPositionCollection($this->map(function (CombatHero $combatHero) {
            return $combatHero->getInitialCombatPosition();
        })))->unique();
    }

    protected function withInitialCombatPosition(CombatPosition $combatPosition)
    {
        return $this->filter(function (CombatHero $combatHero) use ($combatPosition) {
            return $combatHero->getInitialCombatPosition()->id === $combatPosition->id;
        });
    }

    protected function setInheritedCombatPositions(CombatPositionCollection $inheritedCombatPositions)
    {
        return $this->each(function (CombatHero $combatHero) use ($inheritedCombatPositions) {
            $combatHero->setInheritedCombatPositions($inheritedCombatPositions);
        });
    }
}
