<?php

namespace App\Http\Controllers;

use App\Domain\Models\Territory;
use App\Http\Resources\TerritoryResource;
use Illuminate\Http\Request;

class TerritoryController extends Controller
{
    public function index()
    {
        return TerritoryResource::collection(Territory::all());
    }
}
