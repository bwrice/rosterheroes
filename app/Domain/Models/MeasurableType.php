<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MeasurableType
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @method static Builder heroTypes()
 */
class MeasurableType extends Model
{

    const STRENGTH = 'strength';
    const VALOR = 'valor';
    const AGILITY = 'agility';
    const FOCUS = 'focus';
    const APTITUDE = 'aptitude';
    const INTELLIGENCE = 'intelligence';

    const HEALTH = 'health';
    const MANA = 'mana';
    const STAMINA = 'stamina';

    const PASSION = 'passion';
    const BALANCE = 'balance';
    const HONOR = 'honor';
    const PRESTIGE = 'prestige';
    const WRATH = 'wrath';

    protected $guarded = [];

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeHeroTypes(Builder $builder)
    {
        return $builder->whereIn('name', [
            self::STRENGTH,
            self::VALOR,
            self::AGILITY,
            self::FOCUS,
            self::APTITUDE,
            self::INTELLIGENCE,
            self::HEALTH,
            self::STAMINA,
            self::MANA,
            self::PASSION,
            self::BALANCE,
            self::HONOR,
            self::PRESTIGE,
            self::WRATH
        ]);
    }

    public function getBehavior(): MeasurableTypeBehavior
    {
        switch ($this->name) {
            case self::STRENGTH:
                return new MeasurableTypeBehavior('attribute', 20);
            case self::VALOR:
                return new MeasurableTypeBehavior('attribute', 20);
            case self::AGILITY:
                return new MeasurableTypeBehavior('attribute', 20);
            case self::FOCUS:
                return new MeasurableTypeBehavior('attribute', 20);
            case self::APTITUDE:
                return new MeasurableTypeBehavior('attribute', 20);
            case self::INTELLIGENCE:
                return new MeasurableTypeBehavior('attribute', 20);

            case self::HEALTH:
                return new MeasurableTypeBehavior('resource', 250);
            case self::STAMINA:
                return new MeasurableTypeBehavior('resource', 250);
            case self::MANA:
                return new MeasurableTypeBehavior('resource', 250);

            case self::PASSION:
                return new MeasurableTypeBehavior('quality', 100);
            case self::BALANCE:
                return new MeasurableTypeBehavior('quality', 100);
            case self::HONOR:
                return new MeasurableTypeBehavior('quality', 100);
            case self::PRESTIGE:
                return new MeasurableTypeBehavior('quality', 100);
            case self::WRATH:
                return new MeasurableTypeBehavior('quality', 100);
        }

        throw new UnknownBehaviorException($this->name, MeasurableTypeBehavior::class);
    }
}
