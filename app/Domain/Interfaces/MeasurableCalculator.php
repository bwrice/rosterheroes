<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Measurable;

interface MeasurableCalculator
{
    public function getCostToRaise(Measurable $measurable, MeasurableOperator $operator, int $amount = 1): int;

    public function spentOnRaising(Measurable $measurable, MeasurableOperator $operator): int;

    public function getCurrentAmount(Measurable $measurable, MeasurableOperator $operator): int;
}
