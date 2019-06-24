<?php

namespace App\Http\Controllers;

use App\Domain\Models\WeeklyGamePlayer;
use App\Http\Resources\WeeklyGamePlayerResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class WeeklyGamePlayerController extends Controller
{
    public function index()
    {
        $query = WeeklyGamePlayer::query()->forWeek();
        $weeklyGamePlayers = QueryBuilder::for($query)->with([
            'player.positions', 'player.team', 'game.homeTeam', 'game.awayTeam'
        ])->get();
        return WeeklyGamePlayerResource::collection($weeklyGamePlayers);
    }
}
