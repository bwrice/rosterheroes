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

class MaceBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 38;
    public const BASE_DAMAGE_RAGING = 65;

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
        return .38;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .93;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .65;
    }

    public function getBaseDamageModifier(UsesItems $usesItems = null): float
    {
        $strengthModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::STRENGTH)/40;
        $valorModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::VALOR)/80;
        $baseDamageModifier = self::BASE_DAMAGE_RAGING/self::SPEED_RATING;
        return $strengthModifier * $valorModifier * $baseDamageModifier;
    }
}
