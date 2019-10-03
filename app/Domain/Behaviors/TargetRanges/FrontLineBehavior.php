<?php


namespace App\Domain\Behaviors\TargetRanges;


class FrontLineBehavior extends CombatPositionBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage;
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        return $damageModifier;
    }

    public function getAttackerSVG(): string
    {
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0,0,320,320\" style=\"display: block\">
                    <path d=\"M210,30 A1,1 0 0,0 210,290\" fill=\"#808080\"/>
                    <path d=\"M210,60 A1,1 0 0,0 210,260\" fill=\"#808080\" stroke=\"#383838\"/>
                    <path d=\"M210,100 A1,1 0 0,0 210,220\" fill=\"#00ffd1\" stroke=\"#fff\"/>
                </svg>";
    }

    public function getTargetSVG(): string
    {
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0,0,320,320\" style=\"display: block\">
                    <path d=\"M120,290 A1,1 0 0,0 120,30\" fill=\"#808080\"/>
                    <path d=\"M120,260 A1,1 0 0,0 120,60\" fill=\"#808080\" stroke=\"#383838\"/>
                    <path d=\"M120,220 A1,1 0 0,0 120,100\" fill=\"#fc4c1c\" stroke=\"#fff\"/>
                </svg>";
    }

    public function getIconAlt(): string
    {
        return 'Front Line Combat Position';
    }
}
