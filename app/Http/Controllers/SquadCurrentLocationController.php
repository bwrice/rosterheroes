<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Http\Resources\CurrentLocationResource;
use Illuminate\Http\Request;

class SquadCurrentLocationController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);

        $currentLocation = $squad->province->load([
            'continent',
            'territory',
            'borders'
        ])->loadCount([
            'squads',
            'quests'
        ]);

        return new CurrentLocationResource($currentLocation);
    }
}
