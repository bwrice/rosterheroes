<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Facades\Admin;
use App\Mail\SpiritRemovedFromHero;
use App\Notifications\SpiritsDisabledForGame;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class DisableSpiritsForGame
{
    public function execute(Game $game, string $reason = 'N/A', $notifyUser = true)
    {
        $spirits = PlayerSpirit::query()->whereHas('playerGameLog', function (Builder $builder) use ($game) {
            return $builder->where('game_id', '=', $game->id);
        });

        $spiritsCount = $spirits->count();
        $heroesCount = 0;

        $spirits->each(function (PlayerSpirit $playerSpirit) use (&$heroesCount, $notifyUser) {
            $playerSpirit->heroes()->with('squad.user')->chunk(200, function (Collection $heroes) use (&$heroesCount, $playerSpirit, $notifyUser) {

                $heroes->each(function (Hero $hero) use ($playerSpirit, $notifyUser) {
                    // Clear spirit from hero
                    $hero->player_spirit_id = null;
                    $hero->save();

                    // Notify user to replace spirit on hero
                    if ($notifyUser) {
                        Mail::to($hero->squad->user)->queue(new SpiritRemovedFromHero($playerSpirit, $hero));
                    }
                });
                $heroesCount += $heroes->count();
            });
        });

        $spirits->delete();

        Admin::notify(new SpiritsDisabledForGame($game, $spiritsCount, $heroesCount, $reason));
    }
}
