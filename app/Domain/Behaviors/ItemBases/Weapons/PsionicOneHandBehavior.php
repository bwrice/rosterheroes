<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:23 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\SingleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class PsionicOneHandBehavior extends WeaponBehavior
{
    protected $weightModifier = 3;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    protected $staminaCostBase = 5.5;
    protected $manaCostBase = 6;

    protected $staminaCostAdjustmentCoefficient = .75;
    protected $manaCostAdjustmentCoefficient = 1.4;

    public function __construct(WeaponGroup $weaponGroup, SingleArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $agilityBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
        $aptitudeBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        $intelligenceBonus = .014 * $usesItems->getBuffedMeasurableAmount(MeasurableType::INTELLIGENCE);
        return $agilityBonus + $aptitudeBonus + $intelligenceBonus;
    }
}
