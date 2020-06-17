<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/31/19
 * Time: 10:57 PM
 */

namespace App\Domain\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Trait HasNameSlug
 * @package App\Domain\Traits
 *
 * @mixin Model
 */
trait HasNameSlug
{
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * @param $slug
     * @return static|Model
     */
    public static function findSlugOrFail($slug)
    {
        return static::forSlug($slug)->firstOrFail();
    }

    /**
     * @param $slug
     * @return Builder
     */
    public static function forSlug($slug)
    {
        return static::query()->where('slug', '=', $slug);
    }
}
