<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorFactory;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TargetPriority
 * @package App\Domain\Models
 *
 * @property string $name
 */
class TargetPriority extends Model
{
    public const ANY = 'any';

    protected $guarded = [];

    /**
     * @return TargetPriorityBehaviorInterface
     */
    public function getBehavior(): TargetPriorityBehaviorInterface
    {
        /** @var TargetPriorityBehaviorFactory $factory */
        $factory = app(TargetPriorityBehaviorFactory::class);
        return $factory->getBehavior($this->name);
    }
}
