<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\HeroClasses\HeroClassBehavior;
use App\Domain\Behaviors\HeroClasses\RangerBehavior;
use App\Domain\Behaviors\HeroClasses\SorcererBehavior;
use App\Domain\Behaviors\HeroClasses\WarriorBehavior;
use App\Exceptions\UnknownBehaviorException;
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
    public function getBehavior(): HeroClassBehavior
    {
        switch($this->name) {
            case self::WARRIOR:
                return app(WarriorBehavior::class);
            case self::RANGER:
                return app(RangerBehavior::class);
            case self::SORCERER:
                return app(SorcererBehavior::class);
        }

        throw new UnknownBehaviorException($this->name, HeroClassBehavior::class);
    }

    /**
     * @return static
     */
    public static function warrior()
    {
        /** @var HeroClass $heroClass */
        $heroClass = self::query()->where('name', '=', self::WARRIOR)->first();
        return $heroClass;
    }

    /**
     * @return static
     */
    public static function ranger()
    {
        /** @var HeroClass $heroClass */
        $heroClass = self::query()->where('name', '=', self::RANGER)->first();
        return $heroClass;
    }

    /**
     * @return static
     */
    public static function sorcerer()
    {
        /** @var HeroClass $heroClass */
        $heroClass = self::query()->where('name', '=', self::SORCERER)->first();
        return $heroClass;
    }

    public function scopeRequiredStarting(Builder $builder)
    {
        return $builder->whereIn('name', self::REQUIRED_STARTING_CLASSES);
    }

    public function getIcon()
    {
        return $this->getBehavior()->getIcon();
    }
}
