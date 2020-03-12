<?php


namespace App\Exceptions;


use App\Domain\Models\Hero;
use Throwable;

class CalculateHeroFantasyPowerException extends \Exception
{
    public const CODE_NO_PLAYER_SPIRIT = 1;

    /**
     * @var Hero
     */
    protected $hero;

    public function __construct(Hero $hero, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->hero = $hero;
    }
}
