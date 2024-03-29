<?php


namespace App\Domain\Behaviors\TargetRanges;


abstract class CombatPositionBehavior
{
    protected $attackerSVG = '';
    protected $targetSVG = '';
    protected $proximity = 100;
    protected int $outerRadius = 1;
    protected int $innerRadius = 0;
    protected string $allyColor = '#fff';
    protected string $enemyColor = '#fff';

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

    public function getOuterRadius(): int
    {
        return $this->outerRadius;
    }

    public function getInnerRadius(): int
    {
        return $this->innerRadius;
    }

    public function getAllyColor(): string
    {
        return $this->allyColor;
    }

    public function getEnemyColor(): string
    {
        return $this->enemyColor;
    }
}
