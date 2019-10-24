<?php

namespace App\Http\Controllers;

use App\Domain\Actions\RemoveSpellFromHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Spell;
use App\Exceptions\SpellCasterException;
use App\Http\Resources\HeroResource;
use App\Policies\HeroPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RemoveSpellController extends Controller
{
    /**
     * @param $heroSlug
     * @param Request $request
     * @param RemoveSpellFromHeroAction $removeSpellFromHeroAction
     * @return HeroResource
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function __invoke($heroSlug, Request $request, RemoveSpellFromHeroAction $removeSpellFromHeroAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);
        /** @var Spell $spell */
        $spell = Spell::query()->find($request->spell);
        try {
            $removeSpellFromHeroAction->execute($hero, $spell);
        } catch (SpellCasterException $exception) {
            throw ValidationException::withMessages([
                'spellCaster' => $exception->getMessage()
            ]);
        }
        return new HeroResource($hero->fresh(Hero::heroResourceRelations()));
    }
}
