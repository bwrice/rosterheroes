<?php

namespace App\Http\Controllers;

use App\Domain\Models\Support\Squads\SquadBorderTravelCostCalculator;
use App\Exceptions\NotBorderedByException;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Http\Resources\ProvinceResource;
use App\Http\Resources\SquadResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SquadBorderTravelController extends Controller
{
    public function store($squadSlug, $borderSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $border = Province::findSlugOrFail($borderSlug);
        try {
            $squad->borderTravel($border);
            return response()->json($squad->load('province'), 201);
        } catch (NotBorderedByException $exception) {
            throw ValidationException::withMessages([
                'border' => $border->name . ' does not border the location of ' . $squad->name
            ]);
        }
    }

    public function show($squadSlug, $borderSlug, SquadBorderTravelCostCalculator $costCalculator)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $border = Province::findSlugOrFail($borderSlug);
        $cost = $costCalculator->calculateGoldCost($squad, $border);
        return response()->json([
            'data' => [
                'cost' => $cost
            ]
        ], 200);
    }
}
