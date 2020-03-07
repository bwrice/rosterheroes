<?php


namespace App\Domain\Behaviors\TargetRanges;


abstract class CombatPositionBehavior
{
    protected $attackerSVG = '';
    protected $targetSVG = '';
    protected $proximity = 100;

    public function getSVG($attacker = true): string
    {
        return $attacker ? $this->getAttackerSVG() : $this->getTargetSVG();
    }

    public function getAttackerSVG(): string
    {
        return $this->attackerSVG;
    }

    public function getTargetSVG(): string
    {
        return $this->targetSVG;
    }

    public function getProximity(): int
    {
        return $this->proximity;
    }
}
