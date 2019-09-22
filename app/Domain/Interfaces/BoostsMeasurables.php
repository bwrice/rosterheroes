<?php


namespace App\Domain\Interfaces;

use App\Domain\Models\MeasurableType;

interface BoostsMeasurables
{
    public function getBoostAmount(int $boostLevel, MeasurableType $measurableType): int;
}
