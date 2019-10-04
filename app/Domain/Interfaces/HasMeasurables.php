<?php


namespace App\Domain\Interfaces;


use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Models\Measurable;

interface HasMeasurables
{
    public function costToRaiseMeasurable(Measurable $measurable, int $amount = 1): int;

    public function spentOnRaisingMeasurable(Measurable $measurable): int;

    public function getCurrentMeasurableAmount(Measurable $measurable): int;

    public function getBuffedMeasurableAmount(Measurable $measurable): int;

    public function availableExperience(): int;

    public function getMeasurableStartingAmount(MeasurableTypeBehavior $measurableTypeBehavior): int;
}
