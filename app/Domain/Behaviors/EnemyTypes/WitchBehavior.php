<?php


namespace App\Domain\Behaviors\EnemyTypes;


class WitchBehavior extends EnemyTypeBehavior
{
    protected $healthModifierBonus = -.75;
    protected $protectionModifierBonus = -.65;
    protected $blockModifierBonus = -.8;
    protected $baseDamageModifierBonus = 4.4;
    protected $damageMultiplierModifierBonus = 4.4;
    protected $combatSpeedModifierBonus = -.4;
}
