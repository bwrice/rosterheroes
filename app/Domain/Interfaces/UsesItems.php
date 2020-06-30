<?php


namespace App\Domain\Interfaces;


use App\Domain\Collections\AttackCollection;

interface UsesItems extends HasItems
{
    public function getBuffedMeasurableAmount(string $measurableTypeName): int;
}
