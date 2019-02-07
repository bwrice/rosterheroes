<?php

namespace App\Http\Controllers;

use App\Events\HeroCreated;
use App\Events\SquadCreated;
use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\HeroRank;
use App\Http\Resources\Squad as SquadResource;
use App\Province;
use App\Squad;
use App\SquadRank;
use App\Squads\MobileStorage\MobileStorageRank;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SquadController extends Controller
{
    public function store(Request $request)
    {
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

        // Give starting salary, gold and favor to new squad
        $squad->increaseSalary(Squad::STARTING_SALARY);
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

    public function show(Request $request, $squadSlug)
    {
        $squad = Squad::slugOrFail($squadSlug);
        if($squad->inCreationState()) {
            return view('create-squad', [
                'squad' => json_encode(new SquadResource($squad))
            ]);
        }
        return "TODO SPA";
    }
}
