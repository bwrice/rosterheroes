<?php

namespace App\Http\Controllers;

use App\Domain\Actions\AddHeroToSquad;
use App\Events\HeroCreated;
use App\Exceptions\GameStartedException;
use App\Exceptions\HeroPostNotFoundException;
use App\Exceptions\InvalidHeroClassException;
use App\Hero;
use App\HeroClass;
use App\Heroes\HeroPosts\HeroPost;
use App\HeroRace;
use App\HeroRank;
use App\Http\Requests\StoreSquadHero;
use App\Squad;
use App\Squads\HeroClassAvailability;
use App\Squads\HeroPostAvailability;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\HeroResource as HeroResource;

class SquadHeroController extends Controller
{
    /**
     * @param Request $request
     * @param $squadUuid
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, $squadUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);

        $this->validate($request, [
            'name' => 'required|regex:/^[\w\-\s]+$/|between:4,20|unique:heroes,name',
            'race' => 'required|exists:hero_races,name',
            'class' => 'required|exists:hero_classes,name'
        ]);

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name', '=', $request->race)->first();
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->where('name', '=', $request->class)->first();

        $action = new AddHeroToSquad($squad, $heroRace, $heroClass, $request->name);

        try {
            $hero = $action->execute();
            return response()->json(new HeroResource($hero->fresh()), 201);
        } catch (HeroPostNotFoundException $exception) {
            throw ValidationException::withMessages([
                'race' => $squad->name . ' does not have a hero post available for hero race: ' . $exception->getHeroRace()->name
            ]);
        } catch (InvalidHeroClassException $exception) {
            throw ValidationException::withMessages([
                'class' => 'Cannot create hero with hero class of ' . $exception->getHeroClass()->name
            ]);
        }
    }
}
