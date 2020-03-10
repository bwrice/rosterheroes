<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:22 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors\SingleArmBehavior;
use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableType;

class WandBehavior extends WeaponBehavior
{
    protected $weightModifier = 2;
    protected $blockChanceModifier = 0;

    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    public function __construct(WeaponGroup $weaponGroup, SingleArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    protected function getMeasurablesDamageBonus(UsesItems $usesItems): float
    {
        $aptitudeBonus = .0125 * $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
        $intelligenceBonus = .0125 * $usesItems->getBuffedMeasurableAmount(MeasurableType::INTELLIGENCE);
        return $aptitudeBonus + $intelligenceBonus;
    }
}
