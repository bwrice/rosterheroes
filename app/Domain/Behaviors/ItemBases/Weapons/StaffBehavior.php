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
    protected $weightModifier = 7.3;
    protected $blockChanceModifier = .5;

    protected $baseDamageModifierBonus = .4;
    protected $damageMultiplierModifierBonus = .4;
    protected $combatSpeedModifierBonus = -.25;

    protected $staminaCostBase = 6;
    protected $manaCostBase = 12;

    protected $staminaCostAdjustmentCoefficient = 1.3;
    protected $manaCostAdjustmentCoefficient = 2.85;

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $valorBonus = .012 * $usesItems->getBuffedMeasurableAmount(MeasurableType::VALOR);
        $aptitudeBonus = .012 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        $intelligenceBonus = .022 * $usesItems->getBuffedMeasurableAmount(MeasurableType::INTELLIGENCE);
        return $valorBonus + $aptitudeBonus + $intelligenceBonus;
    }
}
