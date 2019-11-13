<?php

namespace App\Http\Controllers;

use App\Domain\Actions\SquadFastTravelAction;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Models\Squad;
use App\Exceptions\SquadTravelException;
use App\Http\Resources\CurrentLocationResource;
use App\Http\Resources\SquadResource;
use App\Nova\Province;
use App\Policies\SquadPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FastTravelController extends Controller
{
    /**
     * @param $squadSlug
     * @param Request $request
     * @param SquadFastTravelAction $fastTravelAction
     * @return CurrentLocationResource
     * @throws AuthorizationException
     */
    public function __invoke($squadSlug, Request $request, SquadFastTravelAction $fastTravelAction)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $travelRoute = new ProvinceCollection();
        foreach($request->travelRoute as $provinceUuid) {
            $province = \App\Domain\Models\Province::findUuidOrFail($provinceUuid);
            $travelRoute->push($province);
        };

        try {
            $fastTravelAction->execute($squad, $travelRoute);

        } catch (SquadTravelException $exception) {

            throw ValidationException::withMessages([
                'travel' => $exception->getMessage()
            ]);
        }
        $currentLocation = $squad->fresh()->province;
        return new CurrentLocationResource($currentLocation);
    }
}
