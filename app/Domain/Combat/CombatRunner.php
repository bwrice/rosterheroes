<?php


namespace App\Domain\Combat;


class CombatRunner
{

    public function execute(CombatGroup $sideA, CombatGroup $sideB)
    {
        $moment = 1;
        while($moment <= 5000) {

            $combatActions = $sideA->getCombatActions($moment);
            $combatActions->each(function (CombatAction $combatAction) use ($sideB) {
                $combatPosition = $combatAction->getTargetPosition();
                $combatants = $sideB->getCombatantsForPosition($combatPosition);
            });
            $moment++;
        }
    }
}
