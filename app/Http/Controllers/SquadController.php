<?php

namespace App\Http\Controllers;

use App\Events\HeroCreated;
use App\Events\SquadCreated;
use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\HeroRank;
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

        return response()->json($squad, 201);
    }
//
//    public function create()
//    {
//        return view('squad-creation', [
//            'squad' => json_encode([
//                'name' => "Blah squad",
//                'id' => 4
//            ])
//        ]);
//    }
}
