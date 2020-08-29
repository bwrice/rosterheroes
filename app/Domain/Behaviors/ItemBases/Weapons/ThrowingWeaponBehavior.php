<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:21 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\SingleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\TwoArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class ThrowingWeaponBehavior extends WeaponBehavior
{
    protected $weightModifier = 4;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = .4;
    protected $damageMultiplierModifierBonus = .4;
    protected $combatSpeedModifierBonus = -.25;

    protected $staminaCostBase = 8;
    protected $manaCostBase = 2.2;

    protected $staminaCostAdjustmentCoefficient = 1.8;
    protected $manaCostAdjustmentCoefficient = 1.8;

    public function __construct(WeaponGroup $weaponGroup, SingleArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $strengthBonus = .0125 * $usesItems->getBuffedMeasurableAmount(MeasurableType::STRENGTH);
        $focusBonus = .0125 * $usesItems->getBuffedMeasurableAmount(MeasurableType::FOCUS);
        return $strengthBonus + $focusBonus;
    }
}
