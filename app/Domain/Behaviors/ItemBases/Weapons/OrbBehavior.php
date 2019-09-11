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

class OrbBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 85;
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
        return .85;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .35;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .5;
    }

    protected function getBaseDamageMeasurablesModifier(UsesItems $usesItems): float
    {
        $focusBonus = .02 * $usesItems->getMeasurableAmount(MeasurableType::FOCUS);
        $aptitudeBonus = .02 * $usesItems->getMeasurableAmount(MeasurableType::APTITUDE);
        $intelligenceBonus = .02 * $usesItems->getMeasurableAmount(MeasurableType::INTELLIGENCE);
        return 1 + ($focusBonus + $aptitudeBonus + $intelligenceBonus);
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
