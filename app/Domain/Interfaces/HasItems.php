<?php


namespace App\Domain\Interfaces;


interface HasItems
{
    public function getMeasurableAmount(string $measurableTypeName): int;
}
