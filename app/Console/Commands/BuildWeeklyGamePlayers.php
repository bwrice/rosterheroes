<?php

namespace App\Console\Commands;

use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Exceptions\InvalidWeekException;
use App\Jobs\CreateWeeklyGamePlayerJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class BuildWeeklyGamePlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'week:build-game-players {week?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build weekly game players';

    public function handle()
    {
        $week = $this->getWeek();
        $games = $week->getValidGames();

        if ($games->isEmpty()) {
            throw new InvalidWeekException($week, "Week ID: " . $week->id . " has zero games");
        }

        $games->loadMissing([
            'homeTeam.players',
            'awayTeam.players'
        ])->each(function (Game $game) use ($week) {
            $game->homeTeam->players->each(function (Player $player) use ($week, $game) {
                CreateWeeklyGamePlayerJob::dispatch($week, $game, $player);
            });
            $game->awayTeam->players->each(function (Player $player) use ($week, $game) {
                CreateWeeklyGamePlayerJob::dispatch($week, $game, $player);
            });
        });
    }

    /**
     * @return Week
     */
    protected function getWeek()
    {
        $weekID = $this->argument('week');
        if ($weekID) {
            $week = Week::query()->findOrFail($weekID);
        } else {
            $week = Week::current();
        }

        if ($week->gamePlayersQueued()) {
            $message = "Weekly Game Players already queued for week with ID: " . $week->id;
            throw new InvalidWeekException($week, $message);
        }
        return $week;
    }
}
