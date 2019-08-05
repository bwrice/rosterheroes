<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Province;

interface TravelsBorders extends HasGold, HasLocation
{
    public function hasBorderTravelCostExemption(Province $border): bool;
}
