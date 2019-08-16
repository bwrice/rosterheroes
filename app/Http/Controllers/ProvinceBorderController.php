<?php

namespace App\Http\Controllers;

use App\Domain\Models\Province;
use App\Http\Resources\ProvinceResource;
use Illuminate\Http\Request;

class ProvinceBorderController extends Controller
{
    public function index($provinceSlug)
    {
        $province = Province::findSlugOrFail($provinceSlug);
        return ProvinceResource::collection($province->borders);
    }
}
