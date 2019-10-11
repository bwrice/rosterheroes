<?php

namespace App\Http\Controllers;

use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Actions\HeroSpiritAction;
use App\Domain\Actions\RemoveSpiritFromHeroAction;
use App\Exceptions\HeroPlayerSpiritException;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Http\Resources\HeroResource;
use App\Http\Resources\SquadCreationHeroResource;
use Illuminate\Validation\ValidationException;

class HeroPlayerSpiritController extends Controller
{
    public function store($heroSlug, $playerSpiritUuid, AddSpiritToHeroAction $action)
    {
        try {
            $hero = $this->executeHeroSpiritAction($heroSlug, $playerSpiritUuid, $action);
            return new HeroResource($hero->fresh(Hero::heroResourceRelations()));

        } catch (HeroPlayerSpiritException $exception) {

            throw ValidationException::withMessages([
                'roster' =>  $exception->getMessage()
            ]);
        }
    }

    public function delete($heroSlug, $playerSpiritUuid, RemoveSpiritFromHeroAction $action)
    {
        try {
            $hero = $this->executeHeroSpiritAction($heroSlug, $playerSpiritUuid, $action);
            return new HeroResource($hero->fresh(Hero::heroResourceRelations()));

        } catch (HeroPlayerSpiritException $exception) {

            throw ValidationException::withMessages([
                'roster' =>  $exception->getMessage()
            ]);
        }
    }

    /**
     * @param $heroSlug
     * @param $playerSpiritUuid
     * @param HeroSpiritAction $domainAction
     * @return Hero
     * @throws HeroPlayerSpiritException
     */
    protected function executeHeroSpiritAction($heroSlug, $playerSpiritUuid, HeroSpiritAction $domainAction): Hero
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $playerSpirit = PlayerSpirit::findUuid($playerSpiritUuid);
        if (! $playerSpirit) {
            throw ValidationException::withMessages(['Player could not be found']);
        }
        return $domainAction->execute($hero, $playerSpirit);
    }
}
