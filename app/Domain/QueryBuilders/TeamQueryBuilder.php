<?php


namespace App\Domain\QueryBuilders;


use App\Domain\Models\Game;
use Illuminate\Database\Eloquent\Builder;

class TeamQueryBuilder extends Builder
{
    public function partOfGame(Game $game)
    {
        return $this->whereHas('homeGame', function (Builder $builder) use ($game) {
            return $builder->where('id', '=', $game->id);
        })->orWhereHas('awayGame', function (Builder $builder) use ($game) {
            return $builder->where('id', '=', $game->id);
        });
    }
}
