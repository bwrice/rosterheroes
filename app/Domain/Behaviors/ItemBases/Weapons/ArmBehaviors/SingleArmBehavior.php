<?php


namespace App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors;


class SingleArmBehavior implements ArmBehaviorInterface
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
