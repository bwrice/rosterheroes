<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Spell;
use App\Domain\Models\Week;
use App\Exceptions\SpellCasterException;

class CastSpellOnHeroAction
{
    public function execute(Hero $hero, Spell $spell)
    {
        if (! Week::current()->adventuringOpen()) {
            throw new SpellCasterException($hero, $spell, "Week is currently locked", SpellCasterException::CODE_WEEK_LOCKED);
        }

        $squad = $hero->getSquad();
        if (! $squad->spells()->where('id', '=', $spell->id)->first()) {
            throw new SpellCasterException($hero, $spell, "Spell is not in library of " . $squad->name, SpellCasterException::CODE_SPELL_NOT_OWNED);
        }

        if ($hero->spells()->where('id', '=', $spell->id)->first()) {
            throw new SpellCasterException($hero, $spell, "Hero already has cast " . $spell->name, SpellCasterException::CODE_SPELL_ALREADY_CASTED);
        }
    }
}
