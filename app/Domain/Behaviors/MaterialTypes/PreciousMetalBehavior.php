<?php


namespace App\Domain\Behaviors\MaterialTypes;


class PreciousMetalBehavior extends MaterialTypeBehavior
{
    protected $protectionModifier = 1;

    public function getWeightModifier(): float
    {
        return 2.15;
    }
}
