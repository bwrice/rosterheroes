<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:17 PM
 */

namespace App\Domain\Behaviors\ItemBases;

use App\Domain\Behaviors\ItemGroup\ItemGroup;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\UsesItems;

abstract class ItemBaseBehavior
{
    /**
     * @var ItemGroup
     */
    private $itemGroup;

    protected $gearSlotsCount = 1;
    protected $validGearSlotTypes = [];

    protected $weightModifier = 1;
    protected $protectionModifier = 1;
    protected $blockChanceModifier = 1;
    protected $resourceCostAmountModifier = 1;
    protected $resourceCostPercentModifier = 1;

    public function __construct(ItemGroup $itemGroup)
    {
        $this->itemGroup = $itemGroup;
    }

    public function getGroupName(): string
    {
        return $this->itemGroup->name();
    }

    public function getGearSlotsCount(): int
    {
        return $this->gearSlotsCount;
    }

    public function getValidGearSlotTypes(): array
    {
        return $this->validGearSlotTypes;
    }

    public function getWeightModifier(): float
    {
        return $this->weightModifier;
    }

    public function getProtectionModifier(): float
    {
        return $this->protectionModifier;
    }

    public function getBlockChanceModifier(): float
    {
        return $this->blockChanceModifier;
    }

    public function getResourceCostAmountModifier(): float
    {
        return $this->resourceCostAmountModifier;
    }

    public function getResourceCostPercentModifier(): float
    {
        return $this->resourceCostPercentModifier;
    }

    abstract public function adjustBaseDamage(float $baseDamage, UsesItems $usesItems = null): float;

    abstract public function adjustDamageMultiplier(float $damageMultiplier, UsesItems $usesItems = null): float;

    abstract public function adjustCombatSpeed(float $combatSpeed, UsesItems $hasItems = null): float;

    abstract public function getResourceCosts(int $attackTier, float $resourceCostMagnitude);

    abstract public function adjustResourceCosts(ResourceCostsCollection $resourceCosts): ResourceCostsCollection;
}
