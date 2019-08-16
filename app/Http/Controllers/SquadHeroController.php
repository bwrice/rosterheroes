<?php

namespace App\Http\Controllers;

use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Actions\CreateHeroAction;
use App\StorableEvents\HeroCreated;
use App\Exceptions\GameStartedException;
use App\Exceptions\HeroPostNotFoundException;
use App\Exceptions\InvalidHeroClassException;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Http\Requests\StoreSquadHero;
use App\Domain\Models\Squad;
use App\Squads\HeroClassAvailability;
use App\Squads\HeroPostAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\HeroResource as HeroResource;

class SquadHeroController extends Controller
{
    /**
     * @param Request $request
     * @param $squadSlug
     * @param AddNewHeroToSquadAction $domainAction
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, $squadSlug, AddNewHeroToSquadAction $domainAction)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);

        $this->validate($request, [
            'name' => 'required|regex:/^[\w\-\s]+$/|between:4,20|unique:heroes,name',
            'race' => 'required|exists:hero_races,name',
            'class' => 'required|exists:hero_classes,name'
        ]);

        /** @var \App\Domain\Models\HeroClass $heroClass */
        $heroClass = HeroClass::query()->where('name', '=', $request->class)->first();
        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name', '=', $request->race)->first();

        try {

            $hero = $domainAction->execute($squad, $request->name, $heroClass, $heroRace);
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
