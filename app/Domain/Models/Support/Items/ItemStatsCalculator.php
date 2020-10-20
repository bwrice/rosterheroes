<?php


namespace App\Domain\Models\Support\Items;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Interfaces\UsesItems;
use App\Facades\ItemTypeFacade;
use App\Facades\MaterialFacade;

class ItemStatsCalculator
{
    protected int $itemTypeID;
    protected int $materialID;
    protected ?UsesItems $usesItems;

    public function __construct(int $itemTypeID,int $materialID, UsesItems $usesItems = null)
    {
        $this->itemTypeID = $itemTypeID;
        $this->materialID = $materialID;
        $this->usesItems = $usesItems;
    }

    public function adjustCombatSpeed(float $speed): float
    {
        $materialBonus = MaterialFacade::speedModifierBonus($this->materialID);
        $combatSpeed = $speed * (1 + $materialBonus);
        $combatSpeed = $this->getItemBaseBehavior()->adjustCombatSpeed($combatSpeed, $this->usesItems);
        return $combatSpeed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $itemTypeBonus = ItemTypeFacade::baseDamageBonus($this->itemTypeID);
        $materialBonus = MaterialFacade::baseDamageModifierBonus($this->materialID);
        $baseDamage = $baseDamage * (1 + $itemTypeBonus + $materialBonus);
        $baseDamage = $this->getItemBaseBehavior()->adjustBaseDamage($baseDamage, $this->usesItems);
        return $baseDamage;
    }

    public function adjustDamageMultiplier(float $damageMultiplier): float
    {
        $itemTypeBonus = ItemTypeFacade::damageMultiplierBonus($this->itemTypeID);
        $materialBonus = MaterialFacade::damageMultiplierModifierBonus($this->materialID);
        $damageMultiplier = $damageMultiplier * (1 + $itemTypeBonus + $materialBonus);
        $damageMultiplier = $this->getItemBaseBehavior()->adjustDamageMultiplier($damageMultiplier, $this->usesItems);
        return $damageMultiplier;
    }

    public function adjustResourceCostAmount(float $amount): int
    {
        return (int) floor($amount * $this->getItemBaseBehavior()->getResourceCostAmountModifier());
    }

    public function adjustResourceCostPercent(float $amount): float
    {
        return $amount * $this->getItemBaseBehavior()->getResourceCostPercentModifier();
    }

    public function getValidGearSlotTypes(): array
    {
        return $this->getItemBaseBehavior()->getValidGearSlotTypes();
    }

    public function getGearSlotsNeededCount(): int
    {
        return $this->getItemBaseBehavior()->getGearSlotsCount();
    }

    public function getItemBaseBehavior(): ItemBaseBehavior
    {
        return ItemTypeFacade::baseBehavior($this->itemTypeID);
    }
}
