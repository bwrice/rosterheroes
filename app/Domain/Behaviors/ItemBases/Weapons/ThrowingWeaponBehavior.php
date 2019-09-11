<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:21 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\SingleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\TwoArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class ThrowingWeaponBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 65;
    public const BASE_DAMAGE_RAGING = 35;

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
        return .18;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .95;
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
        $strengthModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::STRENGTH)/80;
        $agilityModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::AGILITY)/80;
        $focusModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::AGILITY)/35;
        $baseDamageModifier = self::BASE_DAMAGE_RAGING/self::SPEED_RATING;
        return $strengthModifier * $agilityModifier * $agilityModifier * $focusModifier * $baseDamageModifier;
    }
}
