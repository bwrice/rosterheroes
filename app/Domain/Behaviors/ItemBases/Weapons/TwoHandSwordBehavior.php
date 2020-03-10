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
    public const SPEED_RATING = 22;
    public const BASE_DAMAGE_RAGING = 9;

    protected $weightModifier = 13;
    protected $blockChanceModifier = 1.5;

    protected $baseDamageModifierBonus = -.3;
    protected $damageMultiplierModifierBonus = -.3;
    protected $combatSpeedModifierBonus = .25;

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

    protected function getBaseDamageMeasurablesBonus(UsesItems $usesItems): float
    {
        return 1 + $this->getMeasurablesDamageBonus($usesItems);
    }

    protected function getDamageMultiplierMeasurablesBonus(UsesItems $usesItems): float
    {
        return 1 + $this->getMeasurablesDamageBonus($usesItems);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $strengthBonus = .007 * $usesItems->getBuffedMeasurableAmount(MeasurableType::STRENGTH);
        $valorBonus = .02 * $usesItems->getBuffedMeasurableAmount(MeasurableType::VALOR);
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
