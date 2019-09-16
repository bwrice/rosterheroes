<?php


namespace App\Domain\Behaviors\MaterialTypes;


class PsionicBehavior extends MaterialTypeBehavior
{

    public function getWeightModifier(): float
    {
        return 1.2;
    }
}
