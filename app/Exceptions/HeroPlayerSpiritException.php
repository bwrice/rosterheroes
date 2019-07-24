<?php


namespace App\Exceptions;


use App\Domain\Models\Hero;
use App\Nova\PlayerSpirit;
use Throwable;

class HeroPlayerSpiritException extends \Exception
{
    /*
     * Error Codes
     */
    public const WEEK_LOCKED = 1;
    public const INVALID_PLAYER_POSITIONS = 2;
    public const NOT_ENOUGH_ESSENCE = 3;
    public const GAME_STARTED = 4;

    /**
     * @var Hero
     */
    private $hero;
    /**
     * @var PlayerSpirit
     */
    private $playerSpirit;

    public function __construct(Hero $hero, PlayerSpirit $playerSpirit, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->hero = $hero;
        $this->playerSpirit = $playerSpirit;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return PlayerSpirit
     */
    public function getPlayerSpirit(): PlayerSpirit
    {
        return $this->playerSpirit;
    }

    /**
     * @return Hero
     */
    public function getHero(): Hero
    {
        return $this->hero;
    }
}