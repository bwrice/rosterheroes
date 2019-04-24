<?php

namespace App\Http\Controllers;

use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\NotEnoughSalaryException;
use App\Domain\Models\Hero;
use App\Domain\Models\WeeklyGamePlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HeroWeeklyGamePlayerController extends Controller
{
    public function store($heroUuid, $weeklyGamePlayerUid)
    {
        $hero = Hero::uuid($heroUuid);
        if (! $hero) {
            throw ValidationException::withMessages(["Hero could not be found"]);
        }

        $weeklyGamePlayer = WeeklyGamePlayer::uuid($weeklyGamePlayerUid);
        if (! $weeklyGamePlayer) {
            throw ValidationException::withMessages(['Player could not be found']);
        }

        try {
            //TODO middleware
            $hero->addWeeklyGamePlayer($weeklyGamePlayer);
            return response($hero->load('weeklyGamePlayer')->toJson(), 201);

        } catch (InvalidWeekException $exception) {
            Log::error("User attempted to add weekly-game-player from a different week to hero", [
                'user' => Auth::user()->toArray(),
                'hero' => $hero->toArray(),
                'weekly_game_player' => $weeklyGamePlayer->toArray(),
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
                'salary' => "Not enough available salary for " . $weeklyGamePlayer->gamePlayer->player->fullName()
            ]);
        } catch (GameStartedException $exception) {
            throw ValidationException::withMessages([
                'game' => "Game for " . $weeklyGamePlayer->gamePlayer->player->fullName() . " has already started"
            ]);
        }
    }
}
