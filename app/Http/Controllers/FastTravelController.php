<?php

namespace App\Http\Controllers;

use App\Domain\Actions\FastTravelAction;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Models\Squad;
use App\Exceptions\BorderTravelException;
use App\Http\Resources\SquadResource;
use App\Nova\Province;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FastTravelController extends Controller
{
    /**
     * @param $squadUuid
     * @param Request $request
     * @param FastTravelAction $fastTravelAction
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function __invoke($squadUuid, Request $request, FastTravelAction $fastTravelAction)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);

        $travelRoute = new ProvinceCollection();
        foreach($request->travel_route as $provinceUuid) {
            $province = \App\Domain\Models\Province::uuidOrFail($provinceUuid);
            $travelRoute->push($province);
        };

        try {
            $fastTravelAction->execute($squad, $travelRoute);

        } catch (BorderTravelException $exception) {

            throw ValidationException::withMessages([
                'travel' => $exception->getMessage()
            ]);
        }
        return response()->json(new SquadResource($squad->fresh()), 201);
    }
}
