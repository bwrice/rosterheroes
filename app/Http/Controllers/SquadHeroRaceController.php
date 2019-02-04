<?php

namespace App\Http\Controllers;

use App\Squad;
use App\Squads\HeroRaceAvailability;

class SquadHeroRaceController extends Controller
{
    /**
     * @param $squadUuid
     * @param HeroRaceAvailability $heroRaceAvailability
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($squadUuid, HeroRaceAvailability $heroRaceAvailability)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        return response()->json($heroRaceAvailability->get($squad)->values(), 200);
    }
}
