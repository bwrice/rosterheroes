<?php

namespace App\Http\Controllers;

use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Actions\RemoveSpiritFromHeroAction;
use App\Exceptions\HeroPlayerSpiritException;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Http\Resources\HeroResource;
use Illuminate\Validation\ValidationException;

class HeroPlayerSpiritController extends Controller
{
    public function store($heroSlug, $playerSpiritUuid, AddSpiritToHeroAction $action)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $playerSpirit = PlayerSpirit::findUuid($playerSpiritUuid);
        if (! $playerSpirit) {
            throw ValidationException::withMessages(['Player could not be found']);
        }

        try {
            $hero = $action->execute($hero, $playerSpirit);
            return new HeroResource($hero->loadMissing([
                'heroClass',
                'heroRace.positions',
                'playerSpirit.game.homeTeam',
                'playerSpirit.game.awayTeam',
                'playerSpirit.player.positions',
                'playerSpirit.player.team'
            ]));

        } catch (HeroPlayerSpiritException $exception) {

            throw ValidationException::withMessages([
                'roster' =>  $exception->getMessage()
            ]);
        }
    }

    public function delete($heroSlug, $playerSpiritUuid, RemoveSpiritFromHeroAction $action)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $playerSpirit = PlayerSpirit::findUuid($playerSpiritUuid);
        if (! $playerSpirit) {
            throw ValidationException::withMessages(['Player could not be found']);
        }

        try {
            $hero = $action->execute($hero, $playerSpirit);
            return new HeroResource($hero->loadMissing([
                'heroClass',
                'heroRace.positions'
            ]));

        } catch (HeroPlayerSpiritException $exception) {

            throw ValidationException::withMessages([
                'roster' =>  $exception->getMessage()
            ]);
        }
    }
}
