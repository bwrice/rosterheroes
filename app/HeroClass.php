<?php

namespace App;

use App\Heroes\Classes\HeroClassBehavior;
use App\Heroes\Classes\RangerBehavior;
use App\Heroes\Classes\SorcererBehavior;
use App\Heroes\Classes\WarriorBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroClass
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @method static Builder requiredStarting();
 */
class HeroClass extends Model
{
    const WARRIOR = 'warrior';
    const SORCERER = 'sorcerer';
    const RANGER = 'ranger';

    const REQUIRED_STARTING_CLASSES = [
        self::WARRIOR,
        self::SORCERER,
        self::RANGER
    ];

    protected $guarded = [];

    public function toArray()
    {
        return [
            'name' => $this->name
        ];
    }

    /**
     * @return HeroClassBehavior
     */
    public function getBehavior()
    {
        switch( $this->name ) {
            case self::WARRIOR:
                return app()->make(WarriorBehavior::class);
            case self::RANGER:
                return app()->make(RangerBehavior::class);
            case self::SORCERER:
                return app()->make(SorcererBehavior::class);
        }

        throw new \RuntimeException("Unable to determine Hero Class Behavior");
    }

    /**
     * @return static
     */
    public static function warrior()
    {
        return self::where('name', '=', self::WARRIOR)->first();
    }

    /**
     * @return static
     */
    public static function ranger()
    {
        return self::where('name', '=', self::RANGER)->first();
    }

    /**
     * @return static
     */
    public static function sorcerer()
    {
        return self::where('name', '=', self::SORCERER)->first();
    }

    public function scopeRequiredStarting(Builder $builder)
    {
        return $builder->whereIn('name', self::REQUIRED_STARTING_CLASSES);
    }
}
