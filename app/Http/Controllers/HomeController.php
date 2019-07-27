<?php

namespace App\Http\Controllers;

use App\Domain\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        if (Auth::user()) {
            $squads = $user->squads;
        } else {
            $squads = collect();
        }
        return view('home', [
            'user' => $user,
            'squads' => $squads
        ]);
    }
}
