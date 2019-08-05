<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Province;

interface TravelsBorders
{
    public function getAvailableGold(): int;

    public function borderTravelIsFree(Province $border): bool;
}
