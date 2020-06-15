<?php


namespace App\Domain\Models\Support\Squads;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;

class SquadBorderTravelCostCalculator
{
    public function calculateGoldCost(Squad $squad, Province $border)
    {
        $level = $squad->level();
        if ($level < 10) {
            return 0;
        }

        // TODO calculate based on mobile storage weight + level
        return $level;
    }
}
