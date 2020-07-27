<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DisableSpiritsForGame
{
    public function execute(Game $game)
    {
        $spirits = PlayerSpirit::query()->whereHas('playerGameLog', function (Builder $builder) use ($game) {
            return $builder->where('game_id', '=', $game->id);
        });

        $spirits->each(function (PlayerSpirit $playerSpirit) {
            $playerSpirit->heroes()->chunk(200, function (Collection $heroes) {
                $heroes->each(function (Hero $hero) {
                    $hero->player_spirit_id = null;
                    $hero->save();
                });
            });
        });

        $spirits->delete();
    }
}
