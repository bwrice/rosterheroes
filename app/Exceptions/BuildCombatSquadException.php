<?php


namespace App\Exceptions;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use Throwable;

class BuildCombatSquadException extends \RuntimeException
{
    public const CODE_NO_COMBAT_READY_HEROES = 1;

    /**
     * @var Squad
     */
    protected $squad;

    public function __construct(Squad $squad, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->squad = $squad;
    }
}
