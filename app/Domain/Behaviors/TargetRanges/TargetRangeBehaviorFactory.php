<?php


namespace App\Domain\Behaviors\TargetRanges;


use App\Domain\Models\CombatPosition;
use App\Exceptions\UnknownBehaviorException;

class TargetRangeBehaviorFactory
{
    public function getBehavior(string $targetRangeName): CombatPositionBehaviorInterface
    {
        switch ($targetRangeName) {
            case CombatPosition::MELEE:
                return app(MeleeRangeBehavior::class);
            case CombatPosition::MID_RANGE:
                return app(MidRangeBehavior::class);
            case CombatPosition::LONG_RANGE:
                return app(LongRangeBehavior::class);
        }

        throw new UnknownBehaviorException($targetRangeName, CombatPositionBehaviorInterface::class);
    }
}
