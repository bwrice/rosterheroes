<?php

namespace App\Http\Controllers;

use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Actions\HeroSpiritAction;
use App\Domain\Actions\RemoveSpiritFromHeroAction;
use App\Exceptions\HeroPlayerSpiritException;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Http\Resources\HeroResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HeroPlayerSpiritController extends Controller
{
    public function store($heroSlug, Request $request, AddSpiritToHeroAction $action)
    {
        try {
            $hero = $this->executeHeroSpiritAction($heroSlug, $request, $action);
            return new HeroResource($hero->fresh(Hero::heroResourceRelations()));

        } catch (HeroPlayerSpiritException $exception) {

            throw ValidationException::withMessages([
                'roster' =>  $exception->getMessage()
            ]);
        }
    }

    public function delete($heroSlug, Request $request, RemoveSpiritFromHeroAction $action)
    {
        try {
            $hero = $this->executeHeroSpiritAction($heroSlug, $request, $action);
            return new HeroResource($hero->fresh(Hero::heroResourceRelations()));

        } catch (HeroPlayerSpiritException $exception) {

            throw ValidationException::withMessages([
                'roster' =>  $exception->getMessage()
            ]);
        }
    }

    /**
     * @param $heroSlug
     * @param Request $request
     * @param HeroSpiritAction $domainAction
     * @return Hero
     * @throws HeroPlayerSpiritException
     */
    protected function executeHeroSpiritAction($heroSlug, Request $request, HeroSpiritAction $domainAction): Hero
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $playerSpirit = PlayerSpirit::findUuidOrFail($request->spirit);
        return $domainAction->execute($hero, $playerSpirit);
    }
}
