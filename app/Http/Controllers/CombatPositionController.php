<?php

namespace App\Http\Controllers;

use App\Domain\Models\CombatPosition;
use App\Http\Resources\CombatPositionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CombatPositionController extends Controller
{
    public function index()
    {
        $combatPositions = Cache::remember('all_combat_positions', 60 * 60 * 24, function() {
            return CombatPosition::all();
        });

        return CombatPositionResource::collection($combatPositions);
    }
}
