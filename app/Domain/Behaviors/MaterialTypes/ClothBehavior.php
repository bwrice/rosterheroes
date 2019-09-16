<?php


namespace App\Domain\Behaviors\MaterialTypes;


class ClothBehavior extends MaterialTypeBehavior
{

    public function getWeightModifier(): float
    {
        return 1;
    }
}
