<?php


namespace App\Domain\Services\Travel;


use App\Domain\Interfaces\TravelsBorders;
use App\Domain\Models\Province;

class BorderTravelCostCalculator
{
    public function goldCost(TravelsBorders $travelsBorders, Province $border)
    {
        if ($travelsBorders->hasBorderTravelCostExemption($border)) {
            return 0;
        }

        // TODO expense calculation
        return 0;
    }
}
