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

class PoleArmBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 82;
    public const BASE_DAMAGE_RAGING = 30;

    protected $weightModifier = 9;
    protected $blockChanceModifier = 1.7;

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
        $valorBonus = .006 * $usesItems->getBuffedMeasurableAmount(MeasurableType::VALOR);
        $agilityBonus = .012 * $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
        $focusBonus = .006 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        return $valorBonus + $agilityBonus + $focusBonus;
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
