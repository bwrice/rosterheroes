<?php


namespace App\Domain\Behaviors\MaterialTypes;


class BoneBehavior extends MaterialTypeBehavior
{
    protected $protectionModifier = 1.4;

    public function getWeightModifier(): float
    {
        return 1.7;
    }
}
