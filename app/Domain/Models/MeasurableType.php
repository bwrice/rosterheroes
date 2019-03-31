<?php

namespace App\Domain\Models;

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
}
