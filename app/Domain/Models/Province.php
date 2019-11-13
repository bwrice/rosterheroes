<?php

namespace App\Domain\Models;

use App\Domain\Collections\ProvinceCollection;
use App\Domain\Models\Support\ViewBox;
use App\Domain\QueryBuilders\ProvinceQueryBuilder;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Province
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $uuid
 * @property string $color
 * @property string $view_box
 * @property string $vector_paths
 * @property int $continent_id
 * @property int $territory_id
 *
 * @property Continent $continent
 * @property Territory $territory
 * @property ProvinceCollection $borders
 * @property ProvinceCollection $borderedBy
 *
 * @method static Builder bordersCount(int $count)
 * @method static Builder starting
 */
class Province extends EventSourcedModel
{
    use HasNameSlug;

    const STARTING_PROVINCES = [
        'Prasynein',
        'Thona',
        'Joichela',
        'Baoca',
        'Zynden',
        'Keplyos'
    ];

    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new ProvinceCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new ProvinceQueryBuilder($query);
    }

    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }

    public function squads()
    {
        return $this->hasMany(Squad::class);
    }

    public function quests()
    {
        return $this->hasMany(Quest::class);
    }

    /**
     * @return BelongsToMany
     */
    public function borders()
    {
        return $this->belongsToMany(self::class, 'borders', 'province_id', 'border_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function borderedBy()
    {
        return $this->belongsToMany(self::class, 'borders', 'border_id', 'province_id')
            ->withTimestamps();
    }

    public function isBorderedBy(Province $province)
    {
        return in_array($province->id, $this->borderedBy()->pluck('id')->toArray());
    }

    public function getViewBox(): ViewBox
    {
        $viewBoxArray = json_decode($this->view_box, true);
        return new ViewBox(
            $viewBoxArray['pan_x'],
            $viewBoxArray['pan_y'],
            $viewBoxArray['zoom_x'],
            $viewBoxArray['zoom_y']
        );
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
