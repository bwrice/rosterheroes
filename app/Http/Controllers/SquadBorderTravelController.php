<?php

namespace App\Http\Controllers;

use App\Exceptions\NotBorderedByException;
use App\Province;
use App\Squad;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SquadBorderTravelController extends Controller
{
    public function store($squadUuid, $borderUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        $border = Province::uuidOrFail($borderUuid);
        try {
            $squad->borderTravel($border);
            return response()->json($squad->load('province'), 201);
        } catch (NotBorderedByException $exception) {
            throw ValidationException::withMessages([
                'border' => $border->name . ' does not border the location of ' . $squad->name
            ]);
        }
    }
}
