<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\TargetRanges\CombatPositionBehaviorFactory;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehaviorInterface;
use App\Domain\Models\Traits\HasUniqueNames;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TargetRange
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 */
class CombatPosition extends Model
{
    use HasUniqueNames;

    public const MELEE = 'melee';
    public const MID_RANGE = 'mid-range';
    public const LONG_RANGE = 'long-range';

    protected $guarded = [];

    /**
     * @return CombatPositionBehaviorInterface
     */
    public function getBehavior(): CombatPositionBehaviorInterface
    {
        /** @var CombatPositionBehaviorFactory $factory */
        $factory = app(CombatPositionBehaviorFactory::class);
        return $factory->getBehavior($this->name);
    }
}
