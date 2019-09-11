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

    protected function getBaseDamageMeasurablesModifier(UsesItems $usesItems): float
    {
        $strengthBonus = .018 * $usesItems->getMeasurableAmount(MeasurableType::STRENGTH);
        $valorBonus = .025 * $usesItems->getMeasurableAmount(MeasurableType::VALOR);
        return 1 + ($strengthBonus + $valorBonus);
    }

    protected function getStartingSpeedRating(): int
    {
        return self::SPEED_RATING;
    }

    protected function getStartingBaseDamageRating(): int
    {
        return self::BASE_DAMAGE_RAGING;
    }

    protected function getDamageMultiplierMeasurablesModifier(UsesItems $usesItems): float
    {
        return 1;
    }
}
