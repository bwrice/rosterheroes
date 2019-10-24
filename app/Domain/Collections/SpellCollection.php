<?php


namespace App\Domain\Collections;


use App\Domain\Interfaces\SpellCaster;
use App\Domain\Models\Spell;
use Illuminate\Database\Eloquent\Collection;

class SpellCollection extends BoostsMeasurablesCollection
{
    public function manaCost(): int
    {
        return $this->sum(function (Spell $spell) {
            return $spell->manaCost();
        });
    }

    public function setSpellCaster(?SpellCaster $spellCaster)
    {
        $this->each(function (Spell $spell) use ($spellCaster) {
            $spell->setSpellCaster($spellCaster);
        });
        return $this;
    }
}
