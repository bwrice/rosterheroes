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
    protected $protectionModifier = 0;

    protected $damageMultiplierModifierBonus = 0;
    protected $baseDamageModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    protected $validGearSlotTypes = [
        GearSlot::PRIMARY_ARM,
        GearSlot::OFF_ARM,
    ];

    /**
     * @var ArmBehaviorInterface
     */
    private $armBehavior;

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
        return $this->getStartingSpeedRating() * $this->armBehavior->getCombatSpeedModifierBonus();
    }

    abstract protected function getStartingSpeedRating(): int;

    public function getBaseDamageRating(): float
    {
        return $this->getStartingBaseDamageRating() * $this->armBehavior->getDamageMultiplierModifierBonus();
    }

    abstract protected function getStartingBaseDamageRating(): int;

    public function adjustBaseDamage(float $baseDamage, UsesItems $usesItems = null): float
    {
        $measurablesBonus = $usesItems ? $this->getBaseDamageMeasurablesBonus($usesItems) : 1;
        $sumOfBonuses = $measurablesBonus + $this->baseDamageModifierBonus + $this->armBehavior->getBaseDamageModifierBonus();
        return $baseDamage * (1 + $sumOfBonuses);
    }

    public function adjustDamageMultiplier(float $damageMultiplier, UsesItems $usesItems = null): float
    {
        $measurablesBonus = $usesItems ? $this->getDamageMultiplierMeasurablesBonus($usesItems) : 0;
        $sumOfBonuses = $measurablesBonus + $this->damageMultiplierModifierBonus + $this->armBehavior->getDamageMultiplierModifierBonus();
        return $damageMultiplier * (1 + $sumOfBonuses);
    }

    public function adjustCombatSpeed(float $combatSpeed, UsesItems $hasItems = null): float
    {
        $agilityBonus = $hasItems ? $hasItems->getBuffedMeasurableAmount(MeasurableType::AGILITY)/500 : 0;
        $sumOfBonuses = $agilityBonus + $this->combatSpeedModifierBonus + $this->armBehavior->getCombatSpeedModifierBonus();
        return $combatSpeed * (1 + $sumOfBonuses);
    }

    protected function getBaseDamageMeasurablesBonus(UsesItems $usesItems): float
    {
        return 4 * $this->getMeasurablesDamageBonus($usesItems);
    }

    protected function getDamageMultiplierMeasurablesBonus(UsesItems $usesItems)
    {
        return $this->getMeasurablesDamageBonus($usesItems);
    }

    abstract protected function getMeasurablesDamageBonus(UsesItems $usesItems): float;
}
