<?php


namespace App\Domain\Actions;


use App\Domain\Collections\PlayerCollection;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Facades\Admin;
use App\Jobs\CreatePlayerSpiritJob;
use App\Notifications\SpiritsCreatedForGame;

class CreateSpiritsForGame
{
    public function execute(Game $game, Week $week)
    {
        $spirits = Player::query()
            ->withNoSpiritForGame($game)
            ->where('status', '=', Player::STATUS_ROSTER);

        $count = $spirits->count();

        $spirits->each(function (Player $player) use ($game, $week) {
                CreatePlayerSpiritJob::dispatch($week, $game, $player);
            });

        Admin::notify(new SpiritsCreatedForGame($game, $count));
    }
}
