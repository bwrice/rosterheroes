<?php


namespace App\Domain\Behaviors\TargetRanges;


abstract class CombatPositionBehavior implements CombatPositionBehaviorInterface
{
    public function getSVG($attacker = true): string
    {
        return $attacker ? $this->getAttackerSVG() : $this->getTargetSVG();
    }


    abstract public function getAttackerSVG(): string;

    abstract public function getTargetSVG(): string;
}
