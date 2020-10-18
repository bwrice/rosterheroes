<?php


namespace App\Domain\Behaviors\ItemBases\Clothing;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ClothingGroup;
use App\Domain\Interfaces\UsesItems;
use Illuminate\Support\Collection;

abstract class ClothingBehavior extends ItemBaseBehavior
{
    protected $blockChanceModifier = 0;

    public function __construct(ClothingGroup $clothingGroup)
    {
        parent::__construct($clothingGroup);
    }

    public function getDamageMultiplierBonus(UsesItems $usesItems = null): float
    {
        return 0;
    }

    public function getBaseDamageBonus(UsesItems $usesItems = null): float
    {
        return 0;
    }

    public function getCombatSpeedBonus(UsesItems $hasItems = null): float
    {
        return 0;
    }

    public function adjustBaseDamage(float $baseDamage, UsesItems $usesItems = null): float
    {
        return $baseDamage;
    }

    public function adjustDamageMultiplier(float $damageMultiplier, UsesItems $usesItems = null): float
    {
        return $damageMultiplier;
    }

    public function adjustCombatSpeed(float $combatSpeed, UsesItems $hasItems = null): float
    {
        return $combatSpeed;
    }

    public function adjustResourceCosts(Collection $resourceCosts): Collection
    {
        return $resourceCosts;
    }
}
