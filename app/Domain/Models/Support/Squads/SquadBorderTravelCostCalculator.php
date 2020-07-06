<?php


namespace App\Domain\Models\Support\Squads;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;

class SquadBorderTravelCostCalculator
{
    public function calculateGoldCost(Squad $squad, Province $border)
    {
        // TODO calculate based on mobile storage weight + level
        return 0;

//        $level = $squad->level();
//        if ($level < 10) {
//            return 0;
//        }
//        return $level;
    }
}
