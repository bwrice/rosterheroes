<?php


namespace App\Exceptions;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use Throwable;

class ChangeCombatPositionException extends \RuntimeException
{
    public const CODE_WEEK_LOCKED = 1;

    /**
     * @var CombatPosition
     */
    private $combatPosition;
    /**
     * @var Hero
     */
    private $hero;

    public function __construct(CombatPosition $combatPosition, Hero $hero, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->combatPosition = $combatPosition;
        $this->hero = $hero;
    }

    /**
     * @return CombatPosition
     */
    public function getCombatPosition(): CombatPosition
    {
        return $this->combatPosition;
    }

    /**
     * @return Hero
     */
    public function getHero(): Hero
    {
        return $this->hero;
    }
}
