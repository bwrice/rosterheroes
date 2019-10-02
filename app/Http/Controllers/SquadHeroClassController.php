<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Policies\SquadPolicy;
use App\Http\Resources\HeroClassResource;

class SquadHeroClassController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        return response(HeroClassResource::collection($squad->getHeroClassAvailability()), 200);
    }
}
