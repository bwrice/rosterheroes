<?php

namespace App\Http\Controllers;

use App\Http\Resources\HeroRaceResource;
use App\Domain\Models\Squad;

class SquadHeroRaceController extends Controller
{
    /**
     * @param $squadUuid
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($squadUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        return response()->json(HeroRaceResource::collection($squad->getHeroRaceAvailability()), 200);
    }
}
