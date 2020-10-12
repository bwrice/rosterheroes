<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Models\CombatPosition;
use App\Facades\CombatPositionFacade;
use Illuminate\Support\Collection;

class GetClosestInheritedCombatPosition
{
    /**
     * @param Combatant $combatant
     * @param Collection $combatants
     * @return CombatPosition
     */
    public function execute(Combatant $combatant, Collection $combatants)
    {
        /** @var Combatant $closestProximityCombatant */
        $closestProximityCombatant = $combatants->sortBy(function (Combatant $combatant) {
            return CombatPositionFacade::proximity($combatant->getCombatPositionID());
        })->first();

        /*
         * If the combatant belongs to the same combat-position as the closest combatant than it
         * will inherit the closes combat-position of all combat-positions
         */
        if ($combatant->getCombatPositionID() === $closestProximityCombatant->getCombatPositionID()) {
            return CombatPositionFacade::closestProximity();
        }

        return CombatPositionFacade::getReferenceModelByID($combatant->getCombatPositionID());
    }
}
