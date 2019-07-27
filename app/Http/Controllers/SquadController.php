<?php

namespace App\Http\Controllers;

use App\Aggregates\SquadAggregate;
use App\Domain\Actions\CreateNewSquadAction;
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
    public function store(Request $request)
    {
        //TODO authorize (limit squad creation to 1?)
        $request->validate([
            'name' => 'required|unique:squads|between:4,20|regex:/^[\w\-\s]+$/'
        ]);

        $uuid = Str::uuid();

        $createAction = new CreateNewSquadAction(
            $uuid,
            auth()->user()->id,
            $request->name,
            new UpdateSquadSlotsAction($uuid)
        );
        // invoke the action
        $squad = $createAction();

        return response()->json(new SquadResource($squad), 201);
    }

    public function create()
    {
        return view('create-squad', [
            'squad' => null
        ]);
    }

    public function show($squadSlug)
    {
        $squad = Squad::slugOrFail($squadSlug);
        return new SquadResource($squad->loadMissing([
            'heroPosts.hero.heroRace.positions',
            'heroPosts.hero.heroClass',
            'heroPosts.hero.playerSpirit.game.homeTeam',
            'heroPosts.hero.playerSpirit.game.awayTeam',
            'heroPosts.hero.playerSpirit.player.team',
            'heroPosts.hero.playerSpirit.player.positions'
        ]));
    }
}
