<?php


namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\ArmBehaviorInterface;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SlotType;

abstract class WeaponBehavior extends ItemBaseBehavior
{
    /**
     * @var ArmBehaviorInterface
     */
    private $armBehavior;

    public function __construct(WeaponGroup $weaponGroup, ArmBehaviorInterface $armBehavior)
    {
        parent::__construct($weaponGroup);
        $this->armBehavior = $armBehavior;
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEFT_ARM,
            SlotType::RIGHT_ARM
        ];
    }

    public function getSlotsCount(): int
    {
        return $this->armBehavior->getSlotsCount();
    }

    /**
     * higher = faster
     * @return float
     */
    abstract public function itemBaseSpeedModifier(): float;

    /**
     * higher = more variance
     * @return float
     */
    abstract public function getVarianceModifier(): float;

    /**
     * higher = more base damage
     * @return float
     */
    abstract public function itemBaseDamageModifier(): float;


    public function getSpeedRating(): float
    {
        return $this->getStartingSpeedRating() * $this->armBehavior->getSpeedRatingModifier();
    }

    abstract protected function getStartingSpeedRating(): int;

    public function getBaseDamageRating(): float
    {
        return $this->getStartingBaseDamageRating() * $this->armBehavior->getBaseDamageRatingModifier();
    }

    abstract protected function getStartingBaseDamageRating(): int;


    /**
     * @param UsesItems|null $hasItems
     * @return float
     */
    public function getCombatSpeedModifier(UsesItems $hasItems = null): float
    {
        return $this->itemBaseSpeedModifier() / ($this->getSlotsCount() ** .5);
    }

    public function getDamageMultiplierModifier(UsesItems $hasItems = null): float
    {
        return $this->getCombatSpeedModifier() + $this->itemBaseDamageModifier()/10;
    }

}
