<?php


namespace App\Domain\Behaviors\ItemBases\Jewelry;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\JewelryGroup;
use App\Domain\Interfaces\UsesItems;
use Illuminate\Support\Collection;

abstract class JewelryBehavior extends ItemBaseBehavior
{
    protected $protectionModifier = 0;
    protected $blockChanceModifier = 0;

    public function __construct(JewelryGroup $jewelryGroup)
    {
        parent::__construct($jewelryGroup);
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
