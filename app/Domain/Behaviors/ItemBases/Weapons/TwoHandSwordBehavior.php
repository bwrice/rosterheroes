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
use App\Domain\Models\MeasurableType;

class TwoHandSwordBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 32;
    public const BASE_DAMAGE_RAGING = 9;

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
        return .4;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .62;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .95;
    }


    public function getBaseDamageModifier(UsesItems $usesItems = null): float
    {
        $strengthModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::STRENGTH)/60;
        $valorModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::VALOR)/30;
        $baseDamageModifier = self::BASE_DAMAGE_RAGING/self::SPEED_RATING;
        $twoHandBonus = 1.6;
        return $twoHandBonus * $strengthModifier * $valorModifier * $baseDamageModifier;
    }
}
