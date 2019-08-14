<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Measurable;

interface MeasurableCalculator
{
    public function getCostToRaiseMeasurable(Measurable $measurable, MeasurableOperator $operator): int;

    public function getCurrentMeasurableAmount(Measurable $measurable, MeasurableOperator $operator): int;
}
