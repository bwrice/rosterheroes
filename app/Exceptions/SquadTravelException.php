<?php


namespace App\Exceptions;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use Throwable;

class SquadTravelException extends \Exception
{
    /*
     * Error Codes
     */
    public const NOT_BORDERED_BY = 1;
    public const NOT_ENOUGH_GOLD = 2;
    public const WEEK_LOCKED = 3;
    public const MIN_LEVEL_NOT_MET = 4;

    /**
     * @var Squad
     */
    private $squad;
    /**
     * @var Province
     */
    private $border;

    public function __construct(Squad $squad, Province $border, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->squad = $squad;
        $this->border = $border;
    }

    /**
     * @return Squad
     */
    public function getSquad(): Squad
    {
        return $this->squad;
    }

    /**
     * @return Province
     */
    public function getBorder(): Province
    {
        return $this->border;
    }
}
