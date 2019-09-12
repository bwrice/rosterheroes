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
        $strengthBonus = .028 * $usesItems->getMeasurableAmount(MeasurableType::STRENGTH);
        $valorBonus = .0125 * $usesItems->getMeasurableAmount(MeasurableType::VALOR);
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
