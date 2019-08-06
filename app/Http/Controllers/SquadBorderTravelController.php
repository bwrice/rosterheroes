<?php

namespace App\Http\Controllers;

use App\Domain\Services\Travel\BorderTravelCostCalculator;
use App\Exceptions\NotBorderedByException;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Http\Resources\ProvinceResource;
use App\Http\Resources\SquadResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SquadBorderTravelController extends Controller
{
    public function store($squadSlug, $borderSlug)
    {
        $squad = Squad::slugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        $border = Province::slugOrFail($borderSlug);
        try {
            $squad->borderTravel($border);
            return response()->json($squad->load('province'), 201);
        } catch (NotBorderedByException $exception) {
            throw ValidationException::withMessages([
                'border' => $border->name . ' does not border the location of ' . $squad->name
            ]);
        }
    }

    public function get($squadSlug, $borderSlug, BorderTravelCostCalculator $costCalculator)
    {
        $squad = Squad::slugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        $border = Province::slugOrFail($borderSlug);
        $cost = $costCalculator->goldCost($squad, $border);
        return response()->json([
            'squad' => new SquadResource($squad),
            'border' => new ProvinceResource($border),
            'cost' => $cost
        ], 200);
    }
}
