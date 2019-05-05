<?php

namespace App\Domain\Models;

use App\Domain\Collections\HeroRaceCollection;
use App\Domain\Models\HeroRace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class HeroPostType
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property HeroRaceCollection $heroRaces
 */
class HeroPostType extends Model
{
    public const HUMAN = 'human';
    public const ELF = 'elf';
    public const DWARF = 'dwarf';
    public const ORC = 'orc';

    protected const STARTING_SQUAD_POST_TYPES = [
        self::HUMAN,
        self::ELF,
        self::DWARF,
        self::ORC
    ];

    protected $guarded = [];

    public function heroRaces()
    {
        return $this->belongsToMany(HeroRace::class)->withTimestamps();
    }
}
