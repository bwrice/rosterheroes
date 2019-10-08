<?php

namespace App\Http\Controllers;

use App\Domain\Models\HeroClass;
use App\Http\Resources\HeroClassResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HeroClassController extends Controller
{
    public function index()
    {
        $heroClasses = Cache::remember('all_hero_classes', 60 * 60 * 24, function () {
            return HeroClass::all();
        });

        return HeroClassResource::collection($heroClasses);
    }
}
