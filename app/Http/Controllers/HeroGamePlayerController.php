<?php

namespace App\Http\Controllers;

use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\NotEnoughSalaryException;
use App\Domain\Models\Hero;
use App\Domain\Models\GamePlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HeroGamePlayerController extends Controller
{
    public function store($heroUuid, $gamePlayerUuid)
    {
        $hero = Hero::uuid($heroUuid);
        if (! $hero) {
            throw ValidationException::withMessages(["Hero could not be found"]);
        }

        $gamePlayer = GamePlayer::uuid($gamePlayerUuid);
        if (! $gamePlayer) {
            throw ValidationException::withMessages(['Player could not be found']);
        }

        try {
            //TODO middleware
            $hero->addGamePlayer($gamePlayer);
            return response($hero->load('gamePlayer')->toJson(), 201);

        } catch (InvalidWeekException $exception) {
            Log::error("User attempted to add player-game from a different week to hero", [
                'user' => Auth::user()->toArray(),
                'hero' => $hero->toArray(),
                'game_player' => $gamePlayer->toArray(),
                'invalid_week' => $exception->getInvalidWeek()->toArray(),
                'valid_weeks' => $exception->getValidWeeks()->toArray()
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
                'salary' => "Not enough available salary for " . $gamePlayer->player->fullName()
            ]);
        } catch (GameStartedException $exception) {
            throw ValidationException::withMessages([
                'game' => "Game for " . $gamePlayer->player->fullName() . " has already started"
            ]);
        }
    }
}
