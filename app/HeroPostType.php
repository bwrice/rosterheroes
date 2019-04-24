<?php

namespace App;

use App\Domain\Models\HeroRace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class HeroPostType
 * @package App
 *
 * @property string $name
 *
 * @property Collection $heroRaces
 */
class HeroPostType extends Model
{
    const HUMAN = 'human';
    const ELF = 'elf';
    const DWARF = 'dwarf';
    const ORC = 'orc';

    protected $guarded = [];

    public function heroRaces()
    {
        return $this->belongsToMany(HeroRace::class)->withTimestamps();
    }
}
