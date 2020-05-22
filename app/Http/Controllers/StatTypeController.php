<?php

namespace App\Http\Controllers;

use App\Domain\Models\StatType;
use App\Http\Resources\StatTypeResource;
use Illuminate\Http\Request;

class StatTypeController extends Controller
{
    public function index()
    {
        return StatTypeResource::collection(StatType::all());
    }
}
