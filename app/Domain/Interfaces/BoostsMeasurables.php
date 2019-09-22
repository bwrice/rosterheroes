<?php


namespace App\Domain\Interfaces;

interface BoostsMeasurables
{
    public function getAttributeBoostMultiplier(): int;

    public function getQualityBoostMultiplier(): int;

    public function getResourceBoostMultiplier(): int;
}
