<?php


namespace App\Domain\Behaviors\MaterialTypes;


class WoodBehavior extends MaterialTypeBehavior
{

    public function getWeightModifier(): float
    {
        return 1.4;
    }
}
