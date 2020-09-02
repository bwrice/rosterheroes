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

class PsionicTwoHandBehavior extends WeaponBehavior
{
    protected $weightModifier = 7;
    protected $blockChanceModifier = 1.3;

    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    protected $staminaCostBase = 8;
    protected $manaCostBase = 10;

    protected $staminaCostAdjustmentCoefficient = 1.5;
    protected $manaCostAdjustmentCoefficient = 2.7;

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $strengthBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::STRENGTH);
        $aptitudeBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        $intelligenceBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::INTELLIGENCE);
        return $strengthBonus + $aptitudeBonus + $intelligenceBonus;
    }
}
