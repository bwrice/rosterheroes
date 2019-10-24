<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Spell;
use App\Domain\Models\Week;
use App\Exceptions\SpellCasterException;

class RemoveSpellFromHeroAction
{
    public function execute(Hero $hero, Spell $spell): Hero
    {
        if (! Week::current()->adventuringOpen()) {
            throw new SpellCasterException($hero, $spell, "Week is locked", SpellCasterException::CODE_WEEK_LOCKED);
        }
        if (! $hero->spells()->where('id', '=', $spell->id)->first()) {
            throw new SpellCasterException($hero, $spell, "No spell to remove", SpellCasterException::CODE_SPELL_NO_EXISTING_SPELL);
        }

        $hero->spells()->detach($spell);
        return $hero->fresh();
    }
}
