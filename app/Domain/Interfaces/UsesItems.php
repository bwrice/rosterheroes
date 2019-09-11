<?php


namespace App\Domain\Interfaces;


interface UsesItems
{
    public function getMeasurableAmount(string $measurableTypeName): int;
}
