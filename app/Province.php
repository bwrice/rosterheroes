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
 * @method static Builder starting
 */
class Province extends Model
{
    const STARTING_PROVINCES = [
        'Prasynein',
        'Thona',
        'Joichela',
        'Baoca',
        'Zynden',
        'Keplyos'
    ];

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

    /**
     * @return static
     */
    public static function getStarting()
    {
        /** @var Province $province */
        $province = self::starting()->inRandomOrder()->first();
        return $province;
    }

    public function scopeStarting(Builder $builder)
    {
        return $builder->whereIn('name', self::STARTING_PROVINCES);
    }
}
