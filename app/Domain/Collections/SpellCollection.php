<?php


namespace App\Domain\Collections;


use App\Domain\Models\Spell;
use Illuminate\Database\Eloquent\Collection;

class SpellCollection extends Collection
{
    public function manaCost(): int
    {
        return $this->sum(function (Spell $spell) {
            return $spell->manaCost();
        });
    }
}
