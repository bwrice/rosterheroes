<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Http\Resources\HeroClassResource;
use App\Http\Resources\HeroRaceResource;
use App\Http\Resources\SquadCreationHeroResource;
use App\Http\Resources\SquadResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class CommandCenterController extends Controller
{
    public function show(Request $request, $squadSlug, $any = null)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        if (! $any) {
            // redirect to barracks if no sub-page is set
            return redirect('/command-center/' . $squadSlug . '/barracks');
        }

        if ($squad->inCreationState()) {

            $squad->load([
                'heroes',
                'heroPosts.heroPostType.heroRaces'
            ]);

            return view('create-squad', [
                'squad' => json_encode(new SquadResource($squad)),
                'heroes' => json_encode((SquadCreationHeroResource::collection($squad->heroes))),
                'heroClasses' => json_encode(HeroClassResource::collection($squad->getHeroClassAvailability())),
                'heroRaces' => json_encode(HeroRaceResource::collection($squad->getHeroRaceAvailability()))
            ]);
        }

        return view('command-center');
    }
}
