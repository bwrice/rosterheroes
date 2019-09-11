<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:20 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\SingleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class AxeBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 32;
    public const BASE_DAMAGE_RAGING = 75;

    public function __construct(WeaponGroup $weaponGroup, SingleArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    /**
     * Higher = faster
     * @return float
     */
    public function itemBaseSpeedModifier(): float
    {
        return .32;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .85;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .75;
    }

    public function getBaseDamageModifier(UsesItems $usesItems = null): float
    {
        $valorModifier =  1 + $usesItems->getValorAmount()/50;
        $strengthModifier =  1 + $usesItems->getStrengthAmount()/50;
        $baseDamageModifier = self::BASE_DAMAGE_RAGING/self::SPEED_RATING;
        return $strengthModifier * $valorModifier * $baseDamageModifier;
    }
}
