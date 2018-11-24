<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeroRace extends Model
{
    const HUMAN = 'human';
    const ELF = 'elf';
    const DWARF = 'dwarf';
    const ORC = 'orc';

    protected $guarded = [];
}
