<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:22 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\TwoArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;

class PoleArmBehavior extends WeaponBehavior
{

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    /**
     * Higher = faster
     * @return float
     */
    public function itemBaseSpeedModifier(): float
    {
        return .82;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .2;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .8;
    }

    public function getBaseDamageModifier(UsesItems $usesItems = null): float
    {
        return $this->itemBaseDamageModifier() / $this->getCombatSpeedModifier();
    }

    protected function getBaseDamageMeasurablesModifier(UsesItems $usesItems): float
    {
        return 0;
    }

    protected function getStartingSpeedRating(): int
    {
        return 1;
    }

    protected function getStartingBaseDamageRating(): int
    {
        return 1;
    }

}
