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

class DaggerBehavior extends WeaponBehavior
{
    protected $weightModifier = 2.8;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = -.6;
    protected $damageMultiplierModifierBonus = -.6;
    protected $combatSpeedModifierBonus = .5;

    public function __construct(WeaponGroup $weaponGroup, SingleArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $agilityBonus = .0125 * $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
        $focusBonus = .0125 * $usesItems->getBuffedMeasurableAmount(MeasurableType::FOCUS);
        return $agilityBonus + $focusBonus;
    }
}
