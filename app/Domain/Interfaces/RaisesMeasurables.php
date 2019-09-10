<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Measurable;

interface RaisesMeasurables
{
    public function costToRaiseMeasurable(Measurable $measurable, int $amount = 1): int;

    public function spentOnRaisingMeasurable(Measurable $measurable): int;

    public function getCurrentMeasurableAmount(Measurable $measurable): int;

    public function availableExperience(): int;
}
