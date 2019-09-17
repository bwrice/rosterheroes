<?php


namespace App\Domain\Behaviors\MaterialTypes;


class HideBehavior extends MaterialTypeBehavior
{
    protected $protectionModifier = 1.25;

    public function getWeightModifier(): float
    {
        return 1.3;
    }
}
