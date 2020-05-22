<?php

namespace App\Http\Controllers;

use App\Domain\Models\League;
use App\Http\Resources\LeagueResource;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index()
    {
        return LeagueResource::collection(League::all());
    }
}
