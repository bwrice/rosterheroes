<?php

namespace App;

use App\Positions\Position;
use App\Positions\PositionCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroRace
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property PositionCollection $positions
 */
class HeroRace extends Model
{
    const HUMAN = 'human';
    const ELF = 'elf';
    const DWARF = 'dwarf';
    const ORC = 'orc';

    protected $guarded = [];

    public function positions()
    {
        return $this->belongsToMany(Position::class)->withTimestamps();
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
}
