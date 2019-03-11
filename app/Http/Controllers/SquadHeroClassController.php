<?php

namespace App\Http\Controllers;

use App\Squad;
use App\Squads\HeroClassAvailability;
use App\Http\Resources\HeroClassResource as HeroClassResource;

class SquadHeroClassController extends Controller
{
    public function __invoke($squadUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        return response(HeroClassResource::collection($squad->getHeroClassAvailability()), 200);
    }
}
