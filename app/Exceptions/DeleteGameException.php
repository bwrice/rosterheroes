<?php


namespace App\Exceptions;


use App\Domain\Models\Game;
use Throwable;

class DeleteGameException extends \Exception
{
    public const CODE_GAME_HAS_STATS = 1;

    /**
     * @var Game
     */
    protected $game;

    public function __construct(Game $game, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->game = $game;
    }
}
