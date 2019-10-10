<?php

namespace App\Http\Controllers;

use App\Domain\Models\HeroRace;
use App\Http\Resources\HeroRaceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HeroRaceController extends Controller
{
    public function index()
    {
        $heroRaces = Cache::remember('all_hero_races', 60 * 60 * 24, function () {
            return HeroRace::query()->with(['positions'])->get();
        });

        return HeroRaceResource::collection($heroRaces);
    }
}
