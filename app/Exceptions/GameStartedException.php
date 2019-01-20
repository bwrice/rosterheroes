<?php

namespace App\Exceptions;

use App\Game;
use Throwable;

class GameStartedException extends \RuntimeException
{
    protected $game;

    public function setGame(Game $game)
    {
        $this->message = "Game already started at: " . $game->starts_at->format('Y-m-d H:i:s');
        $this->game = $game;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }
}
