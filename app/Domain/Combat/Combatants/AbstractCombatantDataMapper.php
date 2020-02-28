<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractCombatantDataMapper
{
    protected function getInitialHealth(array $data)
    {
        return $data['initialHealth'];
    }

    protected function getProtection(array $data)
    {
        return $data['protection'];
    }

    protected function getBlockChancePercent(array $data)
    {
        return $data['blockChancePercent'];
    }

    protected function getInitialCombatPosition(array $data, Collection $allCombatPositions)
    {
        return $initialCombatPosition = $allCombatPositions->find($data['initialCombatPositionID']);
    }

    protected function setCombatantCurrentHealth(AbstractCombatant $combatant, array $data)
    {
        $combatant->setCurrentHealth($data['currentHealth']);
        return $combatant;
    }

    protected function setInheritedCombatPositions(AbstractCombatant $combatant, array $data, Collection $allCombatPositions)
    {
        $inheritedCombatPositions = $allCombatPositions->filter(function (CombatPosition $combatPosition) use ($data) {
            return in_array($combatPosition->id, $data['inheritedCombatPositionIDs']);
        });
        $combatant->setInheritedCombatPositions($inheritedCombatPositions->values());
        return $combatant;
    }
}
