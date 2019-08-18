<?php

namespace App\Http\Controllers;

use App\Domain\Actions\FastTravelAction;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Models\Squad;
use App\Exceptions\BorderTravelException;
use App\Http\Resources\CurrentLocationResource;
use App\Http\Resources\SquadResource;
use App\Nova\Province;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FastTravelController extends Controller
{
    /**
     * @param $squadSlug
     * @param Request $request
     * @param FastTravelAction $fastTravelAction
     * @return CurrentLocationResource
     * @throws AuthorizationException
     */
    public function __invoke($squadSlug, Request $request, FastTravelAction $fastTravelAction)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);

        $travelRoute = new ProvinceCollection();
        foreach($request->travelRoute as $provinceUuid) {
            $province = \App\Domain\Models\Province::findUuidOrFail($provinceUuid);
            $travelRoute->push($province);
        };

        try {
            $fastTravelAction->execute($squad, $travelRoute);

        } catch (BorderTravelException $exception) {

            throw ValidationException::withMessages([
                'travel' => $exception->getMessage()
            ]);
        }
        $currentLocation = $squad->fresh()->province;
        return new CurrentLocationResource($currentLocation);
    }
}
