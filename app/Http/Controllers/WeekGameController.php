<?php

namespace App\Http\Controllers;

use App\Domain\Models\Week;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeekGameController extends Controller
{
    public function index($weekUuid)
    {
        $week = $weekUuid === 'current' ? Week::current() : Week::findUuidOrFail($weekUuid);

        return Cache::remember('games_for_week_' . $week->id, 60 * 60 * 12, function() use ($week) {

            $games = $week->getValidGames();
            return GameResource::collection($games);
        });
    }
}
