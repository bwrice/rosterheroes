<?php

namespace App\Http\Controllers;

use App\Domain\Models\Team;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TeamController extends Controller
{
    public function index()
    {
        return Cache::remember('all_teams', 60 * 60 * 24 * 7, function() {
            $teams = Team::all();
            return TeamResource::collection($teams);
        });
    }
}
