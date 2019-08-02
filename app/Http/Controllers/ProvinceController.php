<?php

namespace App\Http\Controllers;

use App\Domain\Models\Province;
use App\Http\Resources\ProvinceResource;

class ProvinceController extends Controller
{
    public function index()
    {
        return ProvinceResource::collection(Province::all());
    }
}
