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
    public const SPEED_RATING = 44;
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
        return .4;
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

    protected function getBaseDamageMeasurablesModifier(UsesItems $usesItems): float
    {
        return 1 + $this->getMeasurablesDamageBonus($usesItems);
    }

    protected function getDamageMultiplierMeasurablesBonus(UsesItems $usesItems): float
    {
        return 1 + $this->getMeasurablesDamageBonus($usesItems);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems)
    {
        $strengthBonus = .02 * $usesItems->getMeasurableAmount(MeasurableType::STRENGTH);
        $valorBonus = .02 * $usesItems->getMeasurableAmount(MeasurableType::VALOR);
        return $strengthBonus + $valorBonus;
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
