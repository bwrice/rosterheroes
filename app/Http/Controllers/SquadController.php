<?php

namespace App\Http\Controllers;

use App\Aggregates\SquadAggregate;
use App\Domain\Actions\CreateSquadAction;
use App\Domain\Actions\UpdateSquadSlotsAction;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\SlotType;
use App\StorableEvents\HeroCreated;
use App\StorableEvents\SquadCreated;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Http\Resources\SquadResource;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use App\Domain\Models\MobileStorageRank;
use App\Domain\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HeroResource;
use App\Http\Resources\HeroClassResource;
use App\Http\Resources\HeroRaceResource;
use Illuminate\Support\Str;

class SquadController extends Controller
{
    public function store(Request $request, CreateSquadAction $createSquadAction)
    {
        //TODO authorize (limit squad creation to 1?)
        $request->validate([
            'name' => 'required|unique:squads|between:4,20|regex:/^[\w\s]+$/'
        ]);

        $squad = $createSquadAction->execute(auth()->user()->id, $request->name);

        return response()->json(new SquadResource($squad), 201);
    }

    public function create()
    {
        return view('create-squad', [
            // If squad is still in creation state, Command Center Controller reuses this view
            'squad' => null
        ]);
    }

    public function show($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        return new SquadResource($squad);
    }
}
