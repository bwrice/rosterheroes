<?php


namespace App\Domain\Behaviors\EnemyTypes;


class UndeadBehavior extends EnemyTypeBehavior
{
    protected $healthModifierBonus = .2;
    protected $protectionModifierBonus = .8;
    protected $baseDamageModifierBonus = .25;
}
