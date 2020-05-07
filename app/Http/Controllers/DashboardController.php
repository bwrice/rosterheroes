<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Domain\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        /*
         * TODO: Build actual dashboard
         *
         * For now we redirect to command center or squad creation
         */

        /** @var User $user */
        $user = auth()->user();
        /** @var Squad $squad */
        $squad = $user->squads()->first();
        if ($squad) {
            return redirect()->route('command-center', [
                'squadSlug' => $squad->slug,
                'subPage' => 'barracks'
            ]);
        }

        return redirect()->route('create-squad');
    }
}
