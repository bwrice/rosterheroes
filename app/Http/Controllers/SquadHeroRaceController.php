<?php

namespace App\Http\Controllers;

use App\Http\Resources\HeroRaceResource;
use App\Domain\Models\Squad;
use App\Policies\SquadPolicy;

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
        $this->authorize(SquadPolicy::MANAGE, $squad);
        return response()->json(HeroRaceResource::collection($squad->getHeroRaceAvailability()), 200);
    }
}
