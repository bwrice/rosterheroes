<?php

namespace App\Http\Controllers;

use App\Domain\Actions\ChangeHeroCombatPositionAction;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use App\Exceptions\ChangeCombatPositionException;
use App\Http\Resources\HeroResource;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HeroChangeCombatPositionController extends Controller
{
    public function __invoke($heroSlug, Request $request, ChangeHeroCombatPositionAction $domainAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);

        /** @var CombatPosition $combatPosition */
        $combatPosition = CombatPosition::query()->findOrFail($request->position);

        try {
            $hero = $domainAction->execute($hero, $combatPosition);
        } catch (ChangeCombatPositionException $exception) {

            throw ValidationException::withMessages([
                'combatPosition' => $exception->getMessage()
            ]);
        }

        return new HeroResource($hero->load(Hero::heroResourceRelations()));
    }
}
