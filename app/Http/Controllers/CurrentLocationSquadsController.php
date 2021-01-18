<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Http\Resources\LocalSquadResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class CurrentLocationSquadsController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $squads = $squad->province->squads()
            ->with('heroes')
            ->orderByDesc('updated_at')
            ->take(100)
            ->get();

        return LocalSquadResource::collection($squads);
    }
}
