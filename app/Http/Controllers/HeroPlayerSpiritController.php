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
    public function store($heroUuid, $playerSpiritUuid, AddSpiritToHeroAction $action)
    {
        $hero = Hero::uuid($heroUuid);
        if (! $hero) {
            throw ValidationException::withMessages(["Hero could not be found"]);
        }

        $playerSpirit = PlayerSpirit::uuid($playerSpiritUuid);
        if (! $playerSpirit) {
            throw ValidationException::withMessages(['Player could not be found']);
        }

        try {
            $hero = $action->execute($hero, $playerSpirit);
            return new HeroResource($hero->loadMissing([
                'heroClass',
                'heroRace',
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

    public function delete($heroUuid, $playerSpiritUuid, RemoveSpiritFromHeroAction $action)
    {
        $hero = Hero::uuid($heroUuid);
        if (! $hero) {
            throw ValidationException::withMessages(["Hero could not be found"]);
        }

        $playerSpirit = PlayerSpirit::uuid($playerSpiritUuid);
        if (! $playerSpirit) {
            throw ValidationException::withMessages(['Player could not be found']);
        }

        try {
            $hero = $action->execute($hero, $playerSpirit);
            return new HeroResource($hero->loadMissing([
                'heroClass',
                'heroRace'
            ]));

        } catch (HeroPlayerSpiritException $exception) {

            throw ValidationException::withMessages([
                'roster' =>  $exception->getMessage()
            ]);
        }
    }
}
