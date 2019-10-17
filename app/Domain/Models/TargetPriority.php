<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehavior;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TargetPriority
 * @package App\Domain\Models
 *
 * @property string $name
 */
class TargetPriority extends Model
{
    public const ANY = 'Any';
    public const LOWEST_HEALTH = 'Lowest Health';
    public const HIGHEST_THREAT = 'Highest Threat';

    protected $guarded = [];

    /**
     * @return TargetPriorityBehavior
     */
    public function getBehavior(): TargetPriorityBehavior
    {
        /** @var TargetPriorityBehaviorFactory $factory */
        $factory = app(TargetPriorityBehaviorFactory::class);
        return $factory->getBehavior($this->name);
    }
}
