<?php

namespace App\Http\Controllers;

use App\Domain\Models\Position;
use App\Http\Resources\PositionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Cache::remember('all_positions', 60 * 60 * 24, function() {
            return Position::all();
        });

        return PositionResource::collection($positions);
    }
}
