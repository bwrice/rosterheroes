<?php

namespace App\Http\Controllers;

use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\NotEnoughSalaryException;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HeroPlayerSpiritController extends Controller
{
    public function store($heroUuid, $playerSpiritUuid)
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
            //TODO middleware
            $hero->addPlayerSpirit($playerSpirit);
            return response($hero->load('playerSpirit')->toJson(), 201);

        } catch (InvalidWeekException $exception) {
            Log::error("User attempted to add player spirit from a different week to hero", [
                'user' => Auth::user()->toArray(),
                'hero' => $hero->toArray(),
                'player_spirit' => $playerSpirit->toArray(),
                'invalid_week' => $exception->getWeek()->toArray()
            ]);

            throw ValidationException::withMessages([
                'week' =>  "Can't do that this week"
            ]);
        } catch(InvalidPositionsException $exception) {
            throw ValidationException::withMessages([
                'position' => "Player does not have valid position for hero"
            ]);
        } catch (NotEnoughSalaryException $exception) {
            throw ValidationException::withMessages([
                'salary' => "Not enough available salary for " . $playerSpirit->player->fullName()
            ]);
        } catch (GameStartedException $exception) {
            throw ValidationException::withMessages([
                'game' => "Game for " . $playerSpirit->player->fullName() . " has already started"
            ]);
        }
    }
}
