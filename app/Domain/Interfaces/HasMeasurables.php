<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Measurable;

interface HasMeasurables
{
    public function costToRaiseMeasurable(Measurable $measurable): int;

    public function getCurrentMeasurableAmount(Measurable $measurable): int;
}
