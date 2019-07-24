<?php


namespace App\Exceptions;


use App\Domain\Models\Hero;
use App\Nova\PlayerSpirit;
use Throwable;

class HeroPlayerSpiritException extends \Exception
{
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