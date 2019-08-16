<?php

namespace App\Http\Controllers;

use App\Http\Resources\HeroRaceResource;
use App\Domain\Models\Squad;

class SquadHeroRaceController extends Controller
{
    /**
     * @param $squadSlug
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        return response()->json(HeroRaceResource::collection($squad->getHeroRaceAvailability()), 200);
    }
}
