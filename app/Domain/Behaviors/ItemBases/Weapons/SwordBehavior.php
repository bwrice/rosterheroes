<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:19 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\SingleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class SwordBehavior extends WeaponBehavior
{
    protected $weightModifier = 5;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = -.3;
    protected $damageMultiplierModifierBonus = -.3;
    protected $combatSpeedModifierBonus = .25;

    protected $staminaCostBase = 7.5;
    protected $manaCostBase = 2.3;

    protected $staminaCostAdjustmentCoefficient = 1;
    protected $manaCostAdjustmentCoefficient = 1;

    public function __construct(WeaponGroup $weaponGroup, SingleArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $strengthBonus = .007 * $usesItems->getBuffedMeasurableAmount(MeasurableType::STRENGTH);
        $valorBonus = .007 * $usesItems->getBuffedMeasurableAmount(MeasurableType::VALOR);
        $agilityBonus = .007 * $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
        return $strengthBonus + $valorBonus + $agilityBonus;
    }
}
