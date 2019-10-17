<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Models\TargetPriority;
use App\Exceptions\UnknownBehaviorException;

class TargetPriorityBehaviorFactory
{
    public function getBehavior(string $targetPriorityName): TargetPriorityBehavior
    {
        switch ($targetPriorityName) {
            case TargetPriority::ANY:
                return app(AnyPriorityBehavior::class);
            case TargetPriority::LOWEST_HEALTH:
                return app(LowestHealthPriorityBehavior::class);
            case TargetPriority::HIGHEST_THREAT:
                return app(HighestThreatPriorityBehavior::class);
        }

        throw new UnknownBehaviorException($targetPriorityName, TargetPriorityBehavior::class);
    }
}
