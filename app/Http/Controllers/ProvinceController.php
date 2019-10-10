<?php

namespace App\Http\Controllers;

use App\Domain\Models\Province;
use App\Http\Resources\ProvinceResource;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Cache::remember('all_provinces', 60 * 60 * 24, function() {
            return Province::query()->with(['borders'])->get();
        });

        return ProvinceResource::collection($provinces);
    }
}
