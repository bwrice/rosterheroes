<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:20 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\TwoArmBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class BowBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 65;
    public const BASE_DAMAGE_RAGING = 35;

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
        return .65;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .3;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .35;
    }

    public function getBaseDamageModifier(UsesItems $usesItems = null): float
    {
        $strengthModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::STRENGTH)/80;
        $agilityModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::AGILITY)/80;
        $focusModifier =  1 + $usesItems->getMeasurableAmount(MeasurableType::FOCUS)/35;
        $baseDamageModifier = self::BASE_DAMAGE_RAGING/self::SPEED_RATING;
        $twoHandBonus = 1.6;
        return $twoHandBonus * $strengthModifier * $agilityModifier * $agilityModifier * $focusModifier * $baseDamageModifier;
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
