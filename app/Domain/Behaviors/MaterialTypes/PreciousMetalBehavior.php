<?php


namespace App\Domain\Behaviors\MaterialTypes;


class PreciousMetalBehavior extends MaterialTypeBehavior
{

    public function getWeightModifier(): float
    {
        return 2.15;
    }
}
