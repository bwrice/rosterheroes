<?php

namespace App\Http\Controllers;

use App\Events\HeroCreated;
use App\Events\SquadCreated;
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

class SquadController extends Controller
{
    public function store(Request $request)
    {
        //TODO authorize
        $request->validate([
            'name' => 'required|unique:squads|between:4,20|regex:/^[\w\-\s]+$/'
        ]);

        /** @var Squad $squad */
        $squad = Squad::createWithAttributes([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'squad_rank_id' => SquadRank::getStarting()->id,
            'mobile_storage_rank_id' => MobileStorageRank::getStarting()->id,
            'province_id' => Province::getStarting()->id
        ]);

        // Give starting essence, gold and favor to new squad
        $squad->increaseEssence(Squad::STARTING_ESSENCE);
        $squad->increaseGold(Squad::STARTING_GOLD);
        $squad->increaseFavor(Squad::STARTING_FAVOR);
        $squad->addStartingHeroPosts();
        $squad->addSlots();

        event(new SquadCreated($squad));

        return response()->json(new SquadResource($squad), 201);
    }

    public function create()
    {
        return view('create-squad', [
            'squad' => null
        ]);
    }

    public function show(Request $request, $squadSlug, $any = null)
    {
        $squad = Squad::slugOrFail($squadSlug);
        return new SquadResource($squad->loadMissing([
            'heroPosts.hero.playerSpirit.game.homeTeam',
            'heroPosts.hero.playerSpirit.game.awayTeam',
            'heroPosts.hero.playerSpirit.player.team',
            'heroPosts.hero.playerSpirit.player.positions'
        ]));
    }
}
