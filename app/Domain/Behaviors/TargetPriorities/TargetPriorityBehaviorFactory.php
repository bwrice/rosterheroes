<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Models\TargetPriority;
use App\Exceptions\UnknownBehaviorException;

class TargetPriorityBehaviorFactory
{
    public function getBehavior(string $targetPriorityName): TargetPriorityBehaviorInterface
    {
        switch ($targetPriorityName) {
            case TargetPriority::ANY:
                return app(AnyPriorityBehavior::class);
        }

        throw new UnknownBehaviorException($targetPriorityName, TargetPriorityBehaviorInterface::class);
    }
}
