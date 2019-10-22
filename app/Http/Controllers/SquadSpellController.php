<?php

namespace App\Http\Controllers;

use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Http\Resources\SpellResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class SquadSpellController extends Controller
{
    public function index($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $spells = $squad->spells()->with(Spell::getResourceRelations())->get();
        return SpellResource::collection($spells);
    }
}
