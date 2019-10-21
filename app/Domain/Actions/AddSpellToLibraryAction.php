<?php


namespace App\Domain\Actions;


use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Exceptions\AddSpellException;

class AddSpellToLibraryAction
{
    public function execute(Squad $squad, Spell $spell)
    {
        $existingSpell = $squad->spells()->where('id' , '=', $spell->id)->first();
        if ($existingSpell) {
            throw new AddSpellException($squad, $spell, $squad->name ." already owns " . $spell->name, AddSpellException::CODE_ALREADY_OWNS);
        }

        $squad->getAggregate()->addSpellToLibrary($spell->id)->persist();
    }
}
