<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:20 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\TwoArmBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class BowBehavior extends WeaponBehavior
{
    protected $weightModifier = 8.5;
    protected $blockChanceModifier = .5;

    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    protected $staminaCostBase = 15;
    protected $manaCostBase = 4;

    protected $staminaCostAdjustmentCoefficient = 2;
    protected $manaCostAdjustmentCoefficient = 2;

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $strengthBonus = .006 * $usesItems->getBuffedMeasurableAmount(MeasurableType::STRENGTH);
        $agilityBonus = .008 * $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
        $focusBonus = .008 * $usesItems->getBuffedMeasurableAmount(MeasurableType::FOCUS);
        return $strengthBonus + $agilityBonus + $focusBonus;
    }
}
