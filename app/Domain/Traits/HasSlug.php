<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/31/19
 * Time: 10:57 PM
 */

namespace App\Domain\Traits;


use Illuminate\Database\Eloquent\Builder;

trait HasSlug
{
    use \Spatie\Sluggable\HasSlug;

    /**
     * @param $slug
     * @return static
     */
    public static function findSlugOrFail($slug)
    {
        return static::slug($slug)->firstOrFail();
    }

    /**
     * @param $slug
     * @return Builder
     */
    public static function slug($slug)
    {
        return static::query()->where('slug', '=', $slug);
    }
}
