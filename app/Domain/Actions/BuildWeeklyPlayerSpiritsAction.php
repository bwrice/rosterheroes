<?php


namespace App\Domain\Actions;


use App\Domain\Collections\GameCollection;
use App\Domain\Collections\PlayerCollection;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Exceptions\InvalidWeekException;
use App\Jobs\CreatePlayerSpiritJob;
use Laravel\Telescope\Telescope;

class BuildWeeklyPlayerSpiritsAction
{
    public function execute(Week $week)
    {
        Game::query()->validForWeek($week)->with([
            'homeTeam.players',
            'awayTeam.players'
        ])->chunk(10, function (GameCollection $games) use ($week) {
            $games->each(function (Game $game) use ($week) {
                Player::query()
                    ->withNoSpiritForGame($game)
                    ->where('status', '=', Player::STATUS_ROSTER)
                    ->chunk(100, function (PlayerCollection $players) use ($game, $week) {
                    $players ->each(function (Player $player) use ($game, $week) {
                        CreatePlayerSpiritJob::dispatch($week, $game, $player);
                    });
                });
            });
        });
    }
}
