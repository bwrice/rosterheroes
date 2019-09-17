<?php


namespace App\Domain\Behaviors\MaterialTypes;


class PsionicBehavior extends MaterialTypeBehavior
{
    protected $protectionModifier = 1;

    public function getWeightModifier(): float
    {
        return 1.2;
    }
}
