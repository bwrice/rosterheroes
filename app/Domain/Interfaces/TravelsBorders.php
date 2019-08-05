<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Province;

interface TravelsBorders extends HasGold
{
    public function borderTravelIsFree(Province $border): bool;

    public function getCurrentLocation(): Province;
}
