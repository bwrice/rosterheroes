<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\MeasurableTypes\Attributes\AgilityBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\AptitudeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\FocusBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\IntelligenceBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\StrengthBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\ValorBehavior;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\BalanceBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\HonorBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\PassionBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\PrestigeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\WrathBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\HealthBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ManaBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\StaminaBehavior;
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
                return app(StrengthBehavior::class);
            case self::VALOR:
                return app(ValorBehavior::class);
            case self::AGILITY:
                return app(AgilityBehavior::class);
            case self::FOCUS:
                return app(FocusBehavior::class);
            case self::APTITUDE:
                return app(AptitudeBehavior::class);
            case self::INTELLIGENCE:
                return app(IntelligenceBehavior::class);

            case self::HEALTH:
                return app(HealthBehavior::class);
            case self::STAMINA:
                return app(StaminaBehavior::class);
            case self::MANA:
                return app(ManaBehavior::class);

            case self::PASSION:
                return app(PassionBehavior::class);
            case self::BALANCE:
                return app(BalanceBehavior::class);
            case self::HONOR:
                return app(HonorBehavior::class);
            case self::PRESTIGE:
                return app(PrestigeBehavior::class);
            case self::WRATH:
                return app(WrathBehavior::class);
        }

        throw new UnknownBehaviorException($this->name, MeasurableTypeBehavior::class);
    }

    public function getMeasurableGroup()
    {
        return $this->getBehavior()->getMeasurableGroup();
    }
}
