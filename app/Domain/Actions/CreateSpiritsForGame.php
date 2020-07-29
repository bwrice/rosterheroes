<?php


namespace App\Domain\Actions;


use App\Domain\Collections\PlayerCollection;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Jobs\CreatePlayerSpiritJob;

class CreateSpiritsForGame
{
    public function execute(Game $game, Week $week)
    {
        Player::query()
            ->withNoSpiritForGame($game)
            ->where('status', '=', Player::STATUS_ROSTER)
            ->each(function (Player $player) use ($game, $week) {
                    CreatePlayerSpiritJob::dispatch($week, $game, $player);
            });
    }
}
