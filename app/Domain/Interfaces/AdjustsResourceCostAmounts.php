<?php


namespace App\Domain\Interfaces;


interface AdjustsResourceCostAmounts
{
    public function adjustResourceCostAmount(float $amount): int;

    public function adjustResourceCostPercent(float $amount): float;
}
