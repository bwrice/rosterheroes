<?php

namespace App\Http\Controllers;

use App\Domain\Models\MeasurableType;
use App\Http\Resources\MeasurableTypeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MeasurableTypeController extends Controller
{
    public function index()
    {
        return Cache::remember('all_measurable_types', 60 * 60 * 24 * 7, function() {
            $measurableTypes = MeasurableType::all();
            return MeasurableTypeResource::collection($measurableTypes);
        });
    }
}
