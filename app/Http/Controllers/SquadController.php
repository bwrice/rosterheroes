<?php

namespace App\Http\Controllers;

use App\Aggregates\SquadAggregate;
use App\Domain\Actions\CreateSquadAction;
use App\Domain\Models\User;
use App\Policies\SquadPolicy;
use App\Http\Resources\SquadResource;
use App\Domain\Models\Squad;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SquadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @param CreateSquadAction $createSquadAction
     * @return SquadResource
     * @throws ValidationException
     */
    public function store(Request $request, CreateSquadAction $createSquadAction)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->maxSquadsReached()) {
            throw ValidationException::withMessages([
                'squads' => 'Max squad count reached'
            ]);
        }
        $request->validate([
            'name' => 'required|unique:squads|between:4,20|regex:/^[\w\s]+$/'
        ]);

        $squad = $createSquadAction->execute(auth()->user()->id, $request->name);

        return new SquadResource($squad);
    }

    public function create()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->maxSquadsReached()) {
            return redirect('dashboard');
        }

        return view('create-squad', [
            // If squad is still in creation state, Command Center Controller reuses this view
            'squad' => null
        ]);
    }

    public function show($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        return new SquadResource($squad);
    }
}
