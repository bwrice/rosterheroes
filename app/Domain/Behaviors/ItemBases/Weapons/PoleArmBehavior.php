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

class PoleArmBehavior extends WeaponBehavior
{
    protected $weightModifier = 9;
    protected $blockChanceModifier = 1.7;

    protected $baseDamageModifierBonus = -.6;
    protected $damageMultiplierModifierBonus = -.6;
    protected $combatSpeedModifierBonus = .5;

    protected $staminaCostBase = 10;
    protected $manaCostBase = 3;

    protected $staminaCostAdjustmentCoefficient = 1.7;
    protected $manaCostAdjustmentCoefficient = 1.7;

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $valorBonus = .006 * $usesItems->getBuffedMeasurableAmount(MeasurableType::VALOR);
        $agilityBonus = .012 * $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
        $focusBonus = .006 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        return $valorBonus + $agilityBonus + $focusBonus;
    }
}
