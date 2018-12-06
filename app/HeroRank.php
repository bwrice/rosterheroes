<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroRank
 * @package App
 *
 * @property int $id
 */
class HeroRank extends Model
{
    const PRIVATE = 'private';
    const CORPORAL = 'corporal';
    const SERGEANT = 'sergeant';

    protected $guarded = [];

    /**
     * @return static
     */
    public static function getStarting()
    {
        return self::where('name', '=', self::PRIVATE)->first();
    }
}
