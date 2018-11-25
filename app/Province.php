<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Province
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property Collection $borders
 * @property Collection $borderedBy
 *
 * @method static Builder bordersCount(int $count)
 */
class Province extends Model
{
    protected $guarded = [];

    public function vectorPaths()
    {
        return $this->morphMany(VectorPath::class, 'has_paths');
    }

    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }

    public function borders()
    {
        return $this->belongsToMany(self::class, 'borders', 'province_id', 'border_id')
            ->withTimestamps();
    }

    public function borderedBy()
    {
        return $this->belongsToMany(self::class, 'borders', 'border_id', 'province_id')
            ->withTimestamps();
    }

//    /**
//     * @param Builder $query
//     * @param int $count
//     * @return Builder
//     */
//    public function scopeBordersCount(Builder $query, int $count)
//    {
//        return $query->borders()->count() == $count;
//    }
}
