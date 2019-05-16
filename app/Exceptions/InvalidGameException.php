<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/15/19
 * Time: 10:15 PM
 */

namespace App\Exceptions;


use App\Domain\Models\Game;
use Throwable;

class InvalidGameException extends \RuntimeException
{
    /**
     * @var Game
     */
    private $game;

    public function __construct(Game $game, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->game = $game;
        $message = $message ?: 'Invalid Game: ' . $game->getSimpleDescription();
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }
}