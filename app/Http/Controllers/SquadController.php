<?php

namespace App\Http\Controllers;

use App\Squad;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SquadController extends Controller
{
    public function create(Request $request, Squad $squad)
    {
        /** @var User $user */
        $user = Auth::user();
        $squad = $squad->build( $user, $request->name, $request->heroes );

        return response($squad->toJson(), 201);
    }
}
