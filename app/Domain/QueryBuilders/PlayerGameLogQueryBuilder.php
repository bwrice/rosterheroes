<?php


namespace App\Domain\QueryBuilders;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use Illuminate\Database\Eloquent\Builder;

class PlayerGameLogQueryBuilder extends Builder
{
    public function forPlayer(Player $player)
    {
        return $this->where('player_id', '=', $player->id);
    }

    public function forGame(Game $game)
    {
        return $this->where('game_id', '=', $game->id);
    }

    public function withSpirit()
    {
        return $this->whereHas('playerSpirit');
    }

}
