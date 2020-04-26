<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Http\Resources\HeroResource;
use App\Http\Resources\SquadCreationHeroResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class HeroController extends Controller
{
    public function show($heroSlug)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(SquadPolicy::MANAGE, $hero->squad);
        return new HeroResource($hero->loadMissing(Hero::heroResourceRelations()));
    }
}
