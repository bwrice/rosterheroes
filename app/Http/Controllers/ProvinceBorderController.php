<?php

namespace App\Http\Controllers;

use App\Domain\Models\Province;
use App\Http\Resources\ProvinceResource;
use Illuminate\Http\Request;

class ProvinceBorderController extends Controller
{
    public function show($provinceSlug)
    {
        $province = Province::slugOrFail($provinceSlug);
        return ProvinceResource::collection($province->borders);
    }
}
