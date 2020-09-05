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

class TwoHandAxeBehavior extends WeaponBehavior
{
    protected $weightModifier = 15;
    protected $blockChanceModifier = 1.5;

    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    protected $staminaCostBase = 14;
    protected $manaCostBase = 3.9;

    protected $staminaCostAdjustmentCoefficient = 1.9;
    protected $manaCostAdjustmentCoefficient = 1.9;

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $strengthBonus = .04 * $usesItems->getBuffedMeasurableAmount(MeasurableType::STRENGTH);
        $valorBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::VALOR);
        return $strengthBonus + $valorBonus;
    }
}
