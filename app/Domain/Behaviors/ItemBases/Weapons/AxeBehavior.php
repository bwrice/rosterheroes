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
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\MeasurableType;

class AxeBehavior extends WeaponBehavior
{
    public function __construct(WeaponGroup $weaponGroup, SingleArmBehavior $armBehavior)
    {
        parent::__construct($weaponGroup, $armBehavior);
    }

    /**
     * Higher = faster
     * @return float
     */
    public function itemBaseSpeedModifier(): float
    {
        return .32;
    }

    /**
     * Higher = more variance
     * @return float
     */
    public function getVarianceModifier(): float
    {
        return .85;
    }

    /**
     * higher = more base damage
     * @return float
     */
    public function itemBaseDamageModifier(): float
    {
        return .75;
    }

    public function getBaseDamageModifier(HasItems $hasItems = null): float
    {
        $valorAmount = $hasItems->getCurrentMeasurableAmount(MeasurableType::VALOR);
        $valorAmount = $hasItems->getCurrentMeasurableAmount(MeasurableType::STAMINA);
        return $this->itemBaseDamageModifier() / $this->getCombatSpeedModifier();
    }
}
