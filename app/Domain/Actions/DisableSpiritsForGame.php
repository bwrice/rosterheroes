<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Facades\Admin;
use App\Notifications\SpiritsDisabledForGame;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DisableSpiritsForGame
{
    public function execute(Game $game, string $reason = 'N/A')
    {
        $spirits = PlayerSpirit::query()->whereHas('playerGameLog', function (Builder $builder) use ($game) {
            return $builder->where('game_id', '=', $game->id);
        });

        $spiritsCount = $spirits->count();
        $heroesCount = 0;

        $spirits->each(function (PlayerSpirit $playerSpirit) use (&$heroesCount) {
            $playerSpirit->heroes()->chunk(200, function (Collection $heroes) use (&$heroesCount) {
                $heroes->each(function (Hero $hero) {
                    $hero->player_spirit_id = null;
                    $hero->save();
                });
                $heroesCount += $heroes->count();
            });
        });

        $spirits->delete();

        Admin::notify(new SpiritsDisabledForGame($game, $spiritsCount, $heroesCount, $reason));
    }
}
