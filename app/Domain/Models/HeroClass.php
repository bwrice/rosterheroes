<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\HeroClass\HeroClassBehavior;
use App\Domain\Behaviors\HeroClass\MeasurableBaseBonus;
use App\Domain\Behaviors\HeroClass\RangerBehavior;
use App\Domain\Behaviors\HeroClass\SorcererBehavior;
use App\Domain\Behaviors\HeroClass\WarriorBehavior;
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
                return new HeroClassBehavior(
                    collect([
                        [
                            'measurable_type' => MeasurableType::STRENGTH,
                            'amount' => 30
                        ],
                        [
                            'measurable_type' => MeasurableType::VALOR,
                            'amount' => 40
                        ],
                        [
                            'measurable_type' => MeasurableType::AGILITY,
                            'amount' => 10
                        ],
                        [
                            'measurable_type' => MeasurableType::HEALTH,
                            'amount' => 200
                        ],
                        [
                            'measurable_type' => MeasurableType::STAMINA,
                            'amount' => 50
                        ]
                    ]), [
                        ItemBlueprint::STARTER_SHIELD,
                        ItemBlueprint::STARTER_SWORD
                    ]);
            case self::RANGER:
                return new HeroClassBehavior(
                    collect([
                        [
                            'measurable_type' => MeasurableType::STRENGTH,
                            'amount' => 10
                        ],
                        [
                            'measurable_type' => MeasurableType::AGILITY,
                            'amount' => 30
                        ],
                        [
                            'measurable_type' => MeasurableType::FOCUS,
                            'amount' => 40
                        ],
                        [
                            'measurable_type' => MeasurableType::HEALTH,
                            'amount' => 50
                        ],
                        [
                            'measurable_type' => MeasurableType::STAMINA,
                            'amount' => 150
                        ],
                        [
                            'measurable_type' => MeasurableType::MANA,
                            'amount' => 50
                        ]
                    ]), [
                        ItemBlueprint::STARTER_BOW
                    ]);
            case self::SORCERER:
                return new HeroClassBehavior(
                    collect([
                        [
                            'measurable_type' => MeasurableType::FOCUS,
                            'amount' => 10
                        ],
                        [
                            'measurable_type' => MeasurableType::APTITUDE,
                            'amount' => 40
                        ],
                        [
                            'measurable_type' => MeasurableType::INTELLIGENCE,
                            'amount' => 30
                        ],
                        [
                            'measurable_type' => MeasurableType::HEALTH,
                            'amount' => 50
                        ],
                        [
                            'measurable_type' => MeasurableType::MANA,
                            'amount' => 200
                        ]
                    ]), [
                        ItemBlueprint::STARTER_STAFF
                    ]);
        }

        throw new UnknownBehaviorException($this->name, HeroClassBehavior::class);
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
