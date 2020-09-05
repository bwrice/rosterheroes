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
    protected $weightModifier = 13;
    protected $blockChanceModifier = 1.5;

    protected $baseDamageModifierBonus = -.3;
    protected $damageMultiplierModifierBonus = -.3;
    protected $combatSpeedModifierBonus = .25;

    protected $staminaCostBase = 13;
    protected $manaCostBase = 3.7;

    protected $staminaCostAdjustmentCoefficient = 1.8;
    protected $manaCostAdjustmentCoefficient = 1.8;

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $strengthBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::STRENGTH);
        $valorBonus = .04 * $usesItems->getBuffedMeasurableAmount(MeasurableType::VALOR);
        return $strengthBonus + $valorBonus;
    }
}
