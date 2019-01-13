<?php

namespace App\Http\Controllers;

use App\Hero;
use App\PlayerWeek;
use Illuminate\Http\Request;

class HeroPlayerWeekController extends Controller
{
    public function store($heroUuid, $playerWeekUuid)
    {
        $hero = Hero::uuidOrFail($heroUuid);
        $player = PlayerWeek::uuidOrFail($playerWeekUuid);

        $hero->addPlayerWeek($player);
        return response($hero->load('playerWeek')->toJson(), 201);
    }
}
