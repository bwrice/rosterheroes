<?php

namespace App\Http\Controllers;

use App\Domain\Models\Sport;
use App\Http\Resources\SportResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SportController extends Controller
{
    public function index()
    {
        return Cache::remember('all_ports', 60 * 60 * 24 * 7, function () {
            $sports = Sport::all();
            return SportResource::collection($sports);
        });

    }
}
