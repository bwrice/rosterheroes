<?php


namespace App\Domain\Behaviors\EnemyTypes;


class GiantBehavior extends EnemyTypeBehavior
{
    protected $healthModifierBonus = 2.35;
    protected $protectionModifierBonus = .25;
    protected $blockModifierBonus = .45;
    protected $baseDamageModifierBonus = .35;
    protected $damageMultiplierModifierBonus = .35;
    protected $combatSpeedModifierBonus = -.8;
}
