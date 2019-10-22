<?php

namespace App\Http\Controllers;

use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Actions\CreateHeroAction;
use App\Http\Resources\HeroResource;
use App\Http\Resources\SquadCreationHeroResource;
use App\Policies\SquadPolicy;
use App\StorableEvents\HeroCreated;
use App\Exceptions\GameStartedException;
use App\Exceptions\HeroPostNotFoundException;
use App\Exceptions\InvalidHeroClassException;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\Squad;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SquadHeroController extends Controller
{
    /**
     * @param Request $request
     * @param $squadSlug
     * @param AddNewHeroToSquadAction $domainAction
     * @return JsonResponse
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(Request $request, $squadSlug, AddNewHeroToSquadAction $domainAction)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $this->validate($request, [
            'name' => 'required|regex:/^[\w\-\s]+$/|between:4,20|unique:heroes,name',
            'race' => 'required|exists:hero_races,name',
            'class' => 'required|exists:hero_classes,name'
        ]);

        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->where('name', '=', $request->class)->first();
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name', '=', $request->race)->first();

        try {

            $hero = $domainAction->execute($squad, $request->name, $heroClass, $heroRace);
            return response()->json(new SquadCreationHeroResource($hero->fresh()), 201);

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

    public function index($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $heroes = Hero::query()->amongSquad($squad)->get();

        $heroes->load(Hero::heroResourceRelations());

        return HeroResource::collection($heroes);
    }
}
