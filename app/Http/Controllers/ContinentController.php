<?php

namespace App\Http\Controllers;

use App\Domain\Models\Continent;
use App\Http\Resources\ContinentResource;
use Illuminate\Http\Request;

class ContinentController extends Controller
{
    public function index()
    {
        return ContinentResource::collection(Continent::all());
    }
}
