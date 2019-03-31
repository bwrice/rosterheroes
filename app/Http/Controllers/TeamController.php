<?php

namespace App\Http\Controllers;

use App\Domain\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return response()->json(Team::all()->toArray(), 200);
    }
}
