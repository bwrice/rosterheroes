<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:23 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\SingleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class PsionicOneHandBehavior extends WeaponBehavior
{
    public const SPEED_RATING = 65;
    public const BASE_DAMAGE_RAGING = 40;

    protected $weightModifier = 3;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

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
        return .6;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .1;
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $agilityBonus = .007 * $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
        $aptitudeBonus = .007 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        $intelligenceBonus = .007 * $usesItems->getBuffedMeasurableAmount(MeasurableType::INTELLIGENCE);
        return $agilityBonus + $aptitudeBonus + $intelligenceBonus;
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
