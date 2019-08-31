<?php


namespace App\Domain\Behaviors\TargetRanges;


use App\Domain\Models\TargetRange;
use App\Exceptions\UnknownBehaviorException;

class TargetRangeBehaviorFactory
{
    public function getBehavior(string $targetRangeName): TargetRangeBehaviorInterface
    {
        switch ($targetRangeName) {
            case TargetRange::MELEE:
                return app(MeleeRangeBehavior::class);
            case TargetRange::MID_RANGE:
                return app(MidRangeBehavior::class);
            case TargetRange::LONG_RANGE:
                return app(LongRangeBehavior::class);
        }

        throw new UnknownBehaviorException($targetRangeName, TargetRangeBehaviorInterface::class);
    }
}
