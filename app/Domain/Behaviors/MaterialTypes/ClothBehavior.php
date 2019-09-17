<?php


namespace App\Domain\Behaviors\MaterialTypes;


class ClothBehavior extends MaterialTypeBehavior
{
    protected $protectionModifier = 1;

    public function getWeightModifier(): float
    {
        return 1;
    }
}
