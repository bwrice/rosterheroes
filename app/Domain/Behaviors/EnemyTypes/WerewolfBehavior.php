<?php


namespace App\Domain\Behaviors\EnemyTypes;


class WerewolfBehavior extends EnemyTypeBehavior
{
    protected $healthModifierBonus = -.4;
    protected $protectionModifierBonus = -.6;
    protected $blockModifierBonus = -.6;
    protected $baseDamageModifierBonus = .75;
    protected $damageMultiplierModifierBonus = .75;
    protected $combatSpeedModifierBonus = .35;
}
