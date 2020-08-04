<?php

namespace App\Domain\Models;

use App\Domain\Collections\MerchantCollection;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Collections\QuestCollection;
use App\Domain\Models\Json\ViewBox;
use App\Domain\QueryBuilders\ProvinceQueryBuilder;
use App\Domain\Traits\HasNameSlug;
use App\RecruitmentCamp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
 * @property QuestCollection $quests
 *
 * @property Collection $stashes
 * @property Collection $shops
 * @property Collection $recruitmentCamps
 *
 * @method static Builder bordersCount(int $count)
 * @method static Builder starting
 * @method static ProvinceQueryBuilder query()
 *
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

    public function stashes()
    {
        return $this->hasMany(Stash::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function recruitmentCamps()
    {
        return $this->hasMany(RecruitmentCamp::class);
    }

    /**
     * @return BelongsToMany|ProvinceQueryBuilder
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

    public function hasShops()
    {
        if ($this->relationLoaded('shops')) {
            return $this->shops->count() > 0;
        }
        return $this->shops()->count() > 0;
    }

    public function getMerchants()
    {
        $merchants = new MerchantCollection($this->shops);
        return $merchants->merge($this->recruitmentCamps);
    }
}
