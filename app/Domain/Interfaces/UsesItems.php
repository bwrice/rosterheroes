<?php


namespace App\Domain\Interfaces;


interface UsesItems
{
    public function getBuffedMeasurableAmount(string $measurableTypeName): int;
}
