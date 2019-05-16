<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/14/19
 * Time: 11:35 PM
 */

namespace App\Exceptions;


use App\Domain\Models\Player;
use Throwable;

class InvalidPlayerException extends \RuntimeException
{
    /**
     * @var Player
     */
    private $player;

    public function __construct(Player $player, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->player = $player;
        $message = $message ?: "Player: " . $player->fullName() . " is invalid";
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }
}