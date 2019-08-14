<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Measurable;

interface MeasurableOperator
{
    public function getCostToRaiseBaseAmount(Measurable $measurable): float;

    public function getCostToRaiseExponent(Measurable $measurable): float;

    public function getBaseAmount(Measurable $measurable): int;
}
