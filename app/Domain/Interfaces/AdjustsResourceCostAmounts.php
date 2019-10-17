<?php


namespace App\Domain\Interfaces;


interface AdjustsResourceCostAmounts
{
    public function adjustResourceCostAmount(float $amount): float;

    public function adjustResourceCostPercent(float $amount): float;
}
