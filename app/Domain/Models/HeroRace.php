<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\HeroRace\DwarfBehavior;
use App\Domain\Behaviors\HeroRace\ElfBehavior;
use App\Domain\Behaviors\HeroRace\HeroRaceBehavior;
use App\Domain\Behaviors\HeroRace\HumanBehavior;
use App\Domain\Behaviors\HeroRace\OrcBehavior;
use App\Domain\Collections\HeroRaceCollection;
use App\Domain\Collections\PositionCollection;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class HeroRace
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property PositionCollection $positions
 * @property Collection $heroPostTypes
 *
 * @method static Builder starting()
 */
class HeroRace extends Model
{
    const HUMAN = 'human';
    const ELF = 'elf';
    const DWARF = 'dwarf';
    const ORC = 'orc';

    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new HeroRaceCollection($models);
    }

    /** @return BelongsToMany */
    public function positions()
    {
        return $this->belongsToMany(Position::class)->withTimestamps();
    }

    /** @return BelongsToMany */
    public function heroPostTypes()
    {
        return $this->belongsToMany(HeroPostType::class)->withTimestamps();
    }

    /**
     * @return static
     */
    public static function human()
    {
        return self::where('name', '=', self::HUMAN)->first();
    }

    /**
     * @return static
     */
    public static function elf()
    {
        return self::where('name', '=', self::ELF)->first();
    }

    /**
     * @return static
     */
    public static function dwarf()
    {
        return self::where('name', '=', self::DWARF)->first();
    }

    /**
     * @return static
     */
    public static function orc()
    {
        return self::where('name', '=', self::ORC)->first();
    }

    public function scopeStarting(Builder $builder)
    {
        return $builder->whereIn('name', [
            self::HUMAN,
            self::ELF,
            self::DWARF,
            self::ORC
        ]);
    }

    public function toArray()
    {
        return [
            'name' => $this->name
        ];
    }

    public function getBehavior(): HeroRaceBehavior
    {
        switch ($this->name) {
            case self::HUMAN:
                return app(HumanBehavior::class);
            case self::ELF:
                return app(ElfBehavior::class);
            case self::DWARF:
                return app(DwarfBehavior::class);
            case self::ORC:
                return app(OrcBehavior::class);
        }
        throw new UnknownBehaviorException($this->name, HeroRaceBehavior::class);
    }
}
