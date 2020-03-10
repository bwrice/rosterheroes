<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:21 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\TwoArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class CrossbowBehavior extends WeaponBehavior
{
    protected $weightModifier = 9;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = .4;
    protected $damageMultiplierModifierBonus = .4;
    protected $combatSpeedModifierBonus = -.25;

    public function __construct(WeaponGroup $weaponGroup, TwoArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $focusBonus = .0125 * $usesItems->getBuffedMeasurableAmount(MeasurableType::FOCUS);
        $aptitudeBonus = .0125 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        return $focusBonus + $aptitudeBonus;
    }
}
