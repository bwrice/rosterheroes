<?php


namespace App\Domain\Interfaces;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

interface BoostsMeasurables
{
    public function getMeasurableBoostMultiplier(MeasurableTypeBehavior $measurableTypeBehavior): int;
}
