<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Spell;
use App\Domain\Models\Week;
use App\Exceptions\SpellCasterException;

class RemoveSpellFromHeroAction
{
    public function execute(Hero $hero, Spell $spell)
    {
        if (! Week::current()->adventuringOpen()) {
            throw new SpellCasterException($hero, $spell, "Week is locked", SpellCasterException::CODE_WEEK_LOCKED);
        }
    }
}
