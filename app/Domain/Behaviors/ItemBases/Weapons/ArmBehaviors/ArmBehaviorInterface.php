<?php


namespace App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors;


interface ArmBehaviorInterface
{
    public function getSlotsCount(): int;

    public function getBaseDamageRatingModifier(): float;

    public function getSpeedRatingModifier(): float;
}
