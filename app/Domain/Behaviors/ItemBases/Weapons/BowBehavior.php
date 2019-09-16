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

    protected $weightMultiplier = 8.5;

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

    protected function getBaseDamageMeasurablesModifier(UsesItems $usesItems): float
    {
        return $this->getMeasurablesDamageBonus($usesItems);
    }

    protected function getDamageMultiplierMeasurablesBonus(UsesItems $usesItems): float
    {
        return $this->getMeasurablesDamageBonus($usesItems);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems)
    {
        $strengthBonus = .006 * $usesItems->getMeasurableAmount(MeasurableType::STRENGTH);
        $agilityBonus = .008 * $usesItems->getMeasurableAmount(MeasurableType::AGILITY);
        $focusBonus = .008 * $usesItems->getMeasurableAmount(MeasurableType::FOCUS);
        return $strengthBonus + $agilityBonus + $focusBonus;
    }

    protected function getStartingSpeedRating(): int
    {
        return self::SPEED_RATING;
    }

    protected function getStartingBaseDamageRating(): int
    {
        return self::BASE_DAMAGE_RAGING;
    }

}
