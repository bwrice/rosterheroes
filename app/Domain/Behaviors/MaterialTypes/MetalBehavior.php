<?php


namespace App\Domain\Behaviors\MaterialTypes;


class MetalBehavior extends MaterialTypeBehavior
{
    protected $protectionModifier = 1.5;

    public function getWeightModifier(): float
    {
        return 2;
    }
}
