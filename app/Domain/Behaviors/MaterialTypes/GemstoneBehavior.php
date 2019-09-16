<?php


namespace App\Domain\Behaviors\MaterialTypes;


class GemstoneBehavior extends MaterialTypeBehavior
{

    public function getWeightModifier(): float
    {
        return 2.4;
    }
}
