<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Squads\HeroClassAvailability;
use App\Http\Resources\HeroClassResource as HeroClassResource;

class SquadHeroClassController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::slugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        return response(HeroClassResource::collection($squad->getHeroClassAvailability()), 200);
    }
}
