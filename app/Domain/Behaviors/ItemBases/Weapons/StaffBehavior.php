<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:23 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\TwoArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class StaffBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 35;
    public const BASE_DAMAGE_RAGING = 44;

    protected $weightModifier = 7.3;
    protected $blockChanceModifier = .5;

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
        return .35;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .45;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .4;
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
        $valorBonus = .006 * $usesItems->getMeasurableAmount(MeasurableType::VALOR);
        $aptitudeBonus = .006 * $usesItems->getMeasurableAmount(MeasurableType::APTITUDE);
        $intelligenceBonus = .012 * $usesItems->getMeasurableAmount(MeasurableType::INTELLIGENCE);
        return $valorBonus + $aptitudeBonus + $intelligenceBonus;
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
