<?php


namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\ArmBehaviorInterface;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Support\GearSlots\GearSlot;

abstract class WeaponBehavior extends ItemBaseBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::PRIMARY_ARM,
        GearSlot::OFF_ARM,
    ];

    /**
     * @var ArmBehaviorInterface
     */
    private $armBehavior;

    protected $protectionModifier = 0;

    public function __construct(WeaponGroup $weaponGroup, ArmBehaviorInterface $armBehavior)
    {
        parent::__construct($weaponGroup);
        $this->armBehavior = $armBehavior;
        $this->setResourceCostAmountModifier();
        $this->setResourceCostPercentModifier();
        $this->setGearSlotsCount();
    }

    protected function setResourceCostAmountModifier()
    {
        $this->resourceCostAmountModifier = sqrt($this->getGearSlotsCount());
    }

    protected function setResourceCostPercentModifier()
    {
        $this->resourceCostPercentModifier = $this->getGearSlotsCount() ** .25;
    }

    public function setGearSlotsCount(): int
    {
        return $this->gearSlotsCount = $this->armBehavior->getSlotsCount();
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

    abstract protected function getBaseDamageMeasurablesModifier(UsesItems $usesItems): float;


    public function getBaseDamageBonus(UsesItems $usesItems = null): float
    {
        $measurablesModifier = $usesItems ? $this->getBaseDamageMeasurablesModifier($usesItems) : 1;
        return $measurablesModifier * ($this->getBaseDamageRating()/$this->getSpeedRating());
    }

    public function getDamageMultiplierBonus(UsesItems $usesItems = null): float
    {
        $measurablesBonus = $usesItems ? $this->getDamageMultiplierMeasurablesBonus($usesItems) : 0;
        $damageRatingBonus = $this->getBaseDamageRating()/100;
        $slownessBonus = max((110 - $this->getSpeedRating()) /100, 0);
        return $measurablesBonus + $damageRatingBonus + $slownessBonus;
    }

    /**
     * @param UsesItems|null $hasItems
     * @return float
     */
    public function getCombatSpeedBonus(UsesItems $hasItems = null): float
    {
        $agilityBonus = $hasItems ? $hasItems->getBuffedMeasurableAmount(MeasurableType::AGILITY)/500 : 0;
        $speedRatingBonus = $this->getSpeedRating()/100;
        return $speedRatingBonus + $agilityBonus;
    }

    abstract protected function getDamageMultiplierMeasurablesBonus(UsesItems $usesItems): float;
}
