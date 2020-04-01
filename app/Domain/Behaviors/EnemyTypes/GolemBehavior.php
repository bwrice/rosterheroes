<?php


namespace App\Domain\Behaviors\EnemyTypes;


class GolemBehavior extends EnemyTypeBehavior
{
    protected $healthModifierBonus = 3.5;
    protected $protectionModifierBonus = 8.5;
    protected $blockModifierBonus = .25;
    protected $baseDamageModifierBonus = -.25;
    protected $damageMultiplierModifierBonus = -.25;
    protected $combatSpeedModifierBonus = -.75;
}
