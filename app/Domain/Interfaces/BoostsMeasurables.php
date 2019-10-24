<?php


namespace App\Domain\Interfaces;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Collections\MeasurableBoostCollection;

interface BoostsMeasurables
{
    public function getMeasurableBoostMultiplier(MeasurableTypeBehavior $measurableTypeBehavior): float;

    public function getMeasurableBoosts(): MeasurableBoostCollection;
}
