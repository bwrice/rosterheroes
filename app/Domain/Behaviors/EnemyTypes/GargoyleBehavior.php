<?php


namespace App\Domain\Behaviors\EnemyTypes;


class GargoyleBehavior extends EnemyTypeBehavior
{
    protected $healthModifierBonus = 1.85;
    protected $protectionModifierBonus = .7;
    protected $blockModifierBonus = .2;
    protected $baseDamageModifierBonus = -.4;
    protected $damageMultiplierModifierBonus = -.4;
    protected $combatSpeedModifierBonus = -.6;
}
