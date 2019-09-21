<?php


namespace App\Domain\Behaviors\TargetRanges;


abstract class CombatPositionBehavior implements CombatPositionBehaviorInterface
{
    public function getIcon($attacker = true): array
    {
        $src = $attacker ? $this->attackerIconSrc() : $this->targetIconSrc();
        return [
            'src' => $src,
            'alt' => $this->getIconAlt()
        ];
    }


    abstract public function attackerIconSrc(): string;

    abstract public function targetIconSrc(): string;

    abstract public function getIconAlt(): string;
}
