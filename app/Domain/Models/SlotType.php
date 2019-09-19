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

    public const PRIMARY_ARM = 'primary-arm';
    public const OFF_ARM = 'off-arm';
    public const HEAD = 'head';
    public const TORSO = 'torso';
    public const LEGS = 'legs';
    public const FEET = 'feet';
    public const HANDS = 'hands';
    public const WAIST = 'waist';
    public const NECK = 'neck';
    public const PRIMARY_WRIST = 'primary-wrist';
    public const OFF_WRIST = 'off-wrist';
    public const RING_ONE = 'ring-one';
    public const RING_TWO = 'ring-two';
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
            self::OFF_ARM,
            self::PRIMARY_ARM,
            self::OFF_WRIST,
            self::PRIMARY_WRIST,
            self::RING_TWO,
            self::RING_ONE,
            self::NECK,
            self::WAIST
        ]);
    }
}
