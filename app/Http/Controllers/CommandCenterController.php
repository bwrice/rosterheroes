<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Http\Resources\HeroClassResource;
use App\Http\Resources\HeroRaceResource;
use App\Http\Resources\HeroResource;
use App\Http\Resources\SquadResource;
use Illuminate\Http\Request;

class CommandCenterController extends Controller
{
    public function show(Request $request, $squadSlug, $any = null)
    {
        if (! $any) {
            // redirect to barracks if no sub-page is set
            return redirect('/command-center/' . $squadSlug . '/barracks');
        }

        $squad = Squad::findSlugOrFail($squadSlug);

        if ($squad->inCreationState()) {
            return view('create-squad', [
                'squad' => json_encode(new SquadResource($squad)),
                'heroes' => json_encode((HeroResource::collection($squad->getHeroes()))),
                'heroClasses' => json_encode(HeroClassResource::collection($squad->getHeroClassAvailability())),
                'heroRaces' => json_encode(HeroRaceResource::collection($squad->getHeroRaceAvailability()))
            ]);
        }

        return view('command-center');
    }
}
