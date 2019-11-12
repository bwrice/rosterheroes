<?php


namespace App\Domain\Actions;


use App\Domain\Interfaces\TravelsBorders;
use App\Domain\Models\Province;

class CalculateBorderTravelCostForSquadAction
{
    public function execute(TravelsBorders $travelsBorders, Province $border)
    {
        if ($travelsBorders->hasBorderTravelCostExemption($border)) {
            return 0;
        }

        // TODO expense calculation
        return 10;
    }
}
