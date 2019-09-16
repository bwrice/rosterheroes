<?php


namespace App\Domain\Behaviors\MaterialTypes;


class MetalBehavior extends MaterialTypeBehavior
{

    public function getWeightModifier(): float
    {
        return 2;
    }
}
