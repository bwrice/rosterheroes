<?php


namespace App\Domain\Services\Travel;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;

class SquadBorderTravelCostExemption
{
    public function isExempt(Squad $squad, Province $border): bool
    {
        // TODO: any logic such as free until a certain level, or ally provinces
        return false;
    }
}
