<?php

namespace App;

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

    const RIGHT_ARM = 'right-arm';
    const LEFT_ARM = 'left-arm';
    const TORSO = 'torso';
    const HEAD = 'head';
    const FEET = 'feet';
    const HANDS = 'hands';
    const WAIST = 'waist';
    const NECK = 'neck';
    const RIGHT_WRIST = 'right-wrist';
    const LEFT_WRIST = 'left-wrist';
    const RIGHT_RING = 'right-ring';
    const LEFT_RING = 'left-ring';
    const UNIVERSAL = 'universal';

    protected $guarded = [];

    public function vectorPaths()
    {
        return $this->morphMany(VectorPath::class, 'has_paths');
    }

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
            self::LEFT_ARM,
            self::RIGHT_ARM,
            self::LEFT_WRIST,
            self::RIGHT_WRIST,
            self::LEFT_RING,
            self::RIGHT_RING,
            self::NECK,
            self::WAIST,
            self::TORSO
        ]);
    }
}
