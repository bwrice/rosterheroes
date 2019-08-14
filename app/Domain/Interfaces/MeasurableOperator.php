<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Measurable;

interface MeasurableOperator
{
    public function getCostToRaiseCoefficient(Measurable $measurable): float;

    public function getCostToRaiseExponent(Measurable $measurable): float;

    public function getBaseAmount(Measurable $measurable): int;

    public function getCurrentAmountBonus(Measurable $measurable): int;
}
