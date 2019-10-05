<?php


namespace App\Domain\Interfaces;


use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Models\Measurable;

interface HasMeasurables
{
    public function costToRaiseMeasurable(MeasurableTypeBehavior $measurableTypeBehavior, int $amountAlreadyRaised, int $amountToRaise = 1): int;

    public function spentOnRaisingMeasurable(MeasurableTypeBehavior $measurableTypeBehavior, int $amountRaised): int;

//    public function getCurrentMeasurableAmount(Measurable $measurable): int;

    public function calculateMeasurableBuffedAmount(MeasurableTypeBehavior $measurableTypeBehavior, int $amountRaised): int;

    public function availableExperience(): int;

    public function getMeasurablePreBuffedAmount(MeasurableTypeBehavior $measurableTypeBehavior, int $amountRaised): int;
}
