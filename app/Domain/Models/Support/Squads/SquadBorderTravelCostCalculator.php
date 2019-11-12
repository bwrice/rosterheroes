<?php


namespace App\Domain\Models\Support\Squads;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;

class CalculateBorderTravelCostForSquadAction
{
    public function execute(Squad $squad, Province $border)
    {


        // TODO expense calculation
        return 10;
    }
}
