<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/31/19
 * Time: 10:57 PM
 */

namespace App\Traits;


trait HasSlug
{
    use \Spatie\Sluggable\HasSlug;

    /**
     * @param $slug
     * @return static
     */
    public static function slugOrFail($slug)
    {
        return static::where('slug', '=', $slug)->firstOrFail();
    }
}