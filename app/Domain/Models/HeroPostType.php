<?php

namespace App\Domain\Models;

use App\Domain\Collections\HeroRaceCollection;
use App\Domain\Models\HeroRace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;


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

    public const SQUAD_STARTING_HERO_POST_TYPES = [
        [
            'name' => self::HUMAN,
            'count' => 1
        ],
        [
            'name' => self::ELF,
            'count' => 1
        ],
        [
            'name' => self::DWARF,
            'count' => 1
        ],
        [
            'name' => self::ORC,
            'count' => 1
        ]
    ];

    protected $guarded = [];

    public function heroRaces()
    {
        return $this->belongsToMany(HeroRace::class)->withTimestamps();
    }

    /**
     * Returns a collection of HeroPostTypes with keys for how many
     * should be created for a new Squad
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public static function squadStarting()
    {
        return collect(self::SQUAD_STARTING_HERO_POST_TYPES);
    }
}
