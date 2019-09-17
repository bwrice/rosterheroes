<?php


namespace App\Domain\Behaviors\MaterialTypes;


class WoodBehavior extends MaterialTypeBehavior
{
    protected $protectionModifier = 1.15;

    public function getWeightModifier(): float
    {
        return 1.4;
    }
}
