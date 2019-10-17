<?php


namespace App\Domain\Behaviors\TargetRanges;


class BackLineBehavior extends CombatPositionBehavior
{

    public function getCombatSpeedBonus(): float
    {
        return .25;
    }

    public function getBaseDamageBonus(): float
    {
        return .25;
    }

    public function getDamageMultiplierBonus(): float
    {
        return .25;
    }

    public function getAttackerSVG(): string
    {
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0,0,320,320\" style=\"display: block\">
                    <path d=\"M210,30 A1,1 0 0,0 210,290\" fill=\"#808080\"/>
                    <path d=\"M210,60 A1,1 0 0,0 210,260\" fill=\"#00ffd1\" stroke=\"#fff\"/>
                    <path d=\"M210,100 A1,1 0 0,0 210,220\" fill=\"#808080\" stroke=\"#fff\"/>
                </svg>";
    }

    public function getTargetSVG(): string
    {
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0,0,320,320\" style=\"display: block\">
                    <path d=\"M120,290 A1,1 0 0,0 120,30\" fill=\"#808080\"/>
                    <path d=\"M120,260 A1,1 0 0,0 120,60\" fill=\"#fc4c1c\" stroke=\"white\"/>
                    <path d=\"M120,220 A1,1 0 0,0 120,100\" fill=\"#808080\" stroke=\"white\"/>
                </svg>";
    }

    public function getIconAlt(): string
    {
        return 'Back Line Combat Position';
    }
}
