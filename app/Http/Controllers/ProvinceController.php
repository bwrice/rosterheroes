<?php

namespace App\Http\Controllers;

use App\Domain\Models\Province;
use App\Http\Resources\ProvinceResource;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::query()->with([
            'continent',
            'territory'
        ])->get();

        return ProvinceResource::collection($provinces);
    }
}
