<?php


namespace App\Exceptions;


use App\Domain\Models\Hero;
use App\SquadSnapshot;
use Throwable;

class BuildHeroSnapshotException extends \RuntimeException
{
    public const CODE_INVALID_HERO = 1;
    public const CODE_INVALID_PLAYER_SPIRIT = 2;

    /**
     * @var Hero
     */
    private $hero;
    /**
     * @var SquadSnapshot
     */
    private $squadSnapshot;

    public function __construct(SquadSnapshot $squadSnapshot, Hero $hero, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->hero = $hero;
        $this->squadSnapshot = $squadSnapshot;
    }

    /**
     * @return Hero
     */
    public function getHero(): Hero
    {
        return $this->hero;
    }

    /**
     * @return SquadSnapshot
     */
    public function getSquadSnapshot(): SquadSnapshot
    {
        return $this->squadSnapshot;
    }
}
