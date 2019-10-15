<?php

namespace App\Http\Controllers;

use App\Aggregates\SquadAggregate;
use App\Domain\Actions\CreateSquadAction;
use App\Policies\SquadPolicy;
use App\Http\Resources\SquadResource;
use App\Domain\Models\Squad;
use Illuminate\Http\Request;

class SquadController extends Controller
{
    public function store(Request $request, CreateSquadAction $createSquadAction)
    {
        //TODO authorize (limit squad creation to 1?)
        $request->validate([
            'name' => 'required|unique:squads|between:4,20|regex:/^[\w\s]+$/'
        ]);

        $squad = $createSquadAction->execute(auth()->user()->id, $request->name);

        return response()->json(new SquadResource($squad), 201);
    }

    public function create()
    {
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
