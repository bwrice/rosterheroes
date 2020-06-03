<?php

namespace App\Http\Controllers;

use App\Domain\Models\DamageType;
use App\Http\Resources\DamageTypeResource;
use Illuminate\Support\Facades\Cache;

class DamageTypeController extends Controller
{
    public function index()
    {
        $damageTypes = Cache::remember('all_damage_types', 60 * 60 * 24, function () {
            return DamageType::all();
        });

        return DamageTypeResource::collection($damageTypes);
    }
}
