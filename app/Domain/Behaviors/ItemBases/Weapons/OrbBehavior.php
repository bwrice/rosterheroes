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
    protected $weightModifier = 5.5;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = -.6;
    protected $damageMultiplierModifierBonus = -.6;
    protected $combatSpeedModifierBonus = .5;

    protected $staminaCostBase = 4.8;
    protected $manaCostBase = 9;

    protected $staminaCostAdjustmentCoefficient = .6;
    protected $manaCostAdjustmentCoefficient = 1.55;

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $focusBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::FOCUS);
        $aptitudeBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        $intelligenceBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::INTELLIGENCE);
        return $focusBonus + $aptitudeBonus + $intelligenceBonus;
    }
}
