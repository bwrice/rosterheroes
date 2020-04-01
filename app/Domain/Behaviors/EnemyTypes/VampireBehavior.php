<?php


namespace App\Domain\Behaviors\EnemyTypes;


class VampireBehavior extends EnemyTypeBehavior
{
    protected $healthModifierBonus = .3;
    protected $protectionModifierBonus = -.25;
    protected $blockModifierBonus = -.5;
    protected $baseDamageModifierBonus = .4;
    protected $damageMultiplierModifierBonus = .4;
    protected $combatSpeedModifierBonus = 0;
}
