<?php


namespace App\Exceptions;


use App\Domain\Models\Hero;
use Throwable;

class BuildHeroSnapshotException extends \RuntimeException
{
    public const CODE_INVALID_PLAYER_SPIRIT = 1;

    /**
     * @var Hero
     */
    private $hero;

    public function __construct(Hero $hero, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->hero = $hero;
    }

    /**
     * @return Hero
     */
    public function getHero(): Hero
    {
        return $this->hero;
    }
}
