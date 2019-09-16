<?php


namespace App\Domain\Behaviors\MaterialTypes;


class HideBehavior extends MaterialTypeBehavior
{

    public function getWeightModifier(): float
    {
        return 1.3;
    }
}
