<?php


namespace App\Domain\Behaviors\MaterialTypes;


interface MaterialTypeBehaviorInterface
{
    public function getWeightModifier(): float;
}
