<?php


namespace App\Domain\Behaviors\MaterialTypes;


abstract class MaterialTypeBehavior implements MaterialTypeBehaviorInterface
{
    protected $protectionModifier = 1;

    public function getProtectionModifier(): float
    {
        return $this->protectionModifier;
    }
}
