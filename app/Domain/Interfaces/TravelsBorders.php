<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Province;

interface TravelsBorders extends HasGold
{
    public function hasBorderTravelCostExemption(Province $border): bool;

    public function getCurrentLocation(): Province;
}
