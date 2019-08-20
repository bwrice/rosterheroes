<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Measurable;

interface HasMeasurables
{
    public function costToRaiseMeasurable(Measurable $measurable, int $amount = 1): int;

    public function getCurrentMeasurableAmount(Measurable $measurable): int;
}
