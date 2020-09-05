<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:20 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\SingleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class AxeBehavior extends WeaponBehavior
{
    protected $weightModifier = 5.8;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    protected $staminaCostBase = 8;
    protected $manaCostBase = 2.5;

    protected $staminaCostAdjustmentCoefficient = 1.25;
    protected $manaCostAdjustmentCoefficient = 1.25;

    public function __construct(WeaponGroup $weaponGroup, SingleArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $strengthBonus = .025 * $usesItems->getBuffedMeasurableAmount(MeasurableType::STRENGTH);
        $valorBonus = .025 * $usesItems->getBuffedMeasurableAmount(MeasurableType::VALOR);
        return $strengthBonus + $valorBonus;
    }
}
