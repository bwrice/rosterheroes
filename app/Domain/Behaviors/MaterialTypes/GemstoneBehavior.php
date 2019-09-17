<?php


namespace App\Domain\Behaviors\MaterialTypes;


class GemstoneBehavior extends MaterialTypeBehavior
{
    protected $protectionModifier = 1.6;

    public function getWeightModifier(): float
    {
        return 2.4;
    }
}
