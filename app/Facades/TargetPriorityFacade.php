<?php


namespace App\Facades;


use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehavior;
use App\Services\Models\Reference\TargetPriorityService;

/**
 * Class TargetPriorityFacade
 * @package App\Facades
 *
 * @method static TargetPriorityBehavior getBehavior(int|string $identifier)
 *
 * @see TargetPriorityService
 */
class TargetPriorityFacade
{
    protected static function getFacadeAccessor()
    {
        return TargetPriorityService::class;
    }
}
