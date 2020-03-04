<?php


namespace App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors;


class TwoArmBehavior implements ArmBehaviorInterface
{

    public function getSlotsCount(): int
    {
        return 2;
    }

    public function getBaseDamageRatingModifier(): float
    {
        return 2.25;
    }

    public function getSpeedRatingModifier(): float
    {
        return .8;
    }
}
