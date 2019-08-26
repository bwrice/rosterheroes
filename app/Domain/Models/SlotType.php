<?php

namespace App\Domain\Models;

use App\Domain\Models\VectorPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SlotType
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @method static Builder heroTypes()
 */
class SlotType extends Model
{

    public const RIGHT_ARM = 'right-arm';
    public const LEFT_ARM = 'left-arm';
    public const HEAD = 'head';
    public const TORSO = 'torso';
    public const LEGS = 'legs';
    public const FEET = 'feet';
    public const HANDS = 'hands';
    public const WAIST = 'waist';
    public const NECK = 'neck';
    public const RIGHT_WRIST = 'right-wrist';
    public const LEFT_WRIST = 'left-wrist';
    public const RIGHT_RING = 'right-ring';
    public const LEFT_RING = 'left-ring';
    public const UNIVERSAL = 'universal';

    protected $guarded = [];

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeHeroTypes(Builder $builder)
    {
        return $builder->whereIn('name', [
            self::FEET,
            self::HANDS,
            self::HEAD,
            self::TORSO,
            self::LEGS,
            self::LEFT_ARM,
            self::RIGHT_ARM,
            self::LEFT_WRIST,
            self::RIGHT_WRIST,
            self::LEFT_RING,
            self::RIGHT_RING,
            self::NECK,
            self::WAIST
        ]);
    }
}
