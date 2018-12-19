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
        return self::private();
    }

    /**
     * @return static
     */
    public static function private()
    {
        return self::where('name', '=', self::PRIVATE)->first();
    }

    /**
     * @return static
     */
    public static function corporal()
    {
        return self::where('name', '=', self::CORPORAL)->first();
    }

    /**
     * @return static
     */
    public static function sergeant()
    {
        return self::where('name', '=', self::SERGEANT)->first();
    }
}
