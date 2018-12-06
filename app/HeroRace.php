<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroRace
 * @package App
 *
 * @property int $id
 * @property Collection $positions
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
}
