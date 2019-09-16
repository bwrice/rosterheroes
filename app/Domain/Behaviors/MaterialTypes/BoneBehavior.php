<?php


namespace App\Domain\Behaviors\MaterialTypes;


class BoneBehavior extends MaterialTypeBehavior
{

    public function getWeightModifier(): float
    {
        return 1.7;
    }
}
