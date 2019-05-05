<?php

namespace App\Domain\Models;

use App\Domain\Collections\HeroRaceCollection;
use App\Domain\Models\Position;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\HeroPostType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroRace
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property PositionCollection $positions
 * @property Collection $heroPostTypes
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

    public function positions()
    {
        return $this->belongsToMany(Position::class)->withTimestamps();
    }

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

    public function toArray()
    {
        return [
            'name' => $this->name
        ];
    }
}
