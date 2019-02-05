<?php

namespace App\Http\Controllers;

use App\Squad;
use App\Squads\HeroClassAvailability;

class SquadHeroClassController extends Controller
{
    public function __invoke($squadUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        return response($squad->getHeroClassAvailability()->values(), 200);
    }
}
