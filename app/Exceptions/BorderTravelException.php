<?php


namespace App\Exceptions;


use App\Domain\Interfaces\TravelsBorders;
use App\Domain\Models\Province;
use Throwable;

class BorderTravelException extends \Exception
{
    /*
     * Error Codes
     */
    public const NOT_BORDERED_BY = 1;
    public const NOT_ENOUGH_GOLD = 2;
    public const WEEK_LOCKED = 3;
    /**
     * @var TravelsBorders
     */
    private $travelsBorders;
    /**
     * @var Province
     */
    private $border;

    public function __construct(TravelsBorders $travelsBorders, Province $border, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->travelsBorders = $travelsBorders;
        $this->border = $border;
    }

    /**
     * @return TravelsBorders
     */
    public function getTravelsBorders(): TravelsBorders
    {
        return $this->travelsBorders;
    }

    /**
     * @return Province
     */
    public function getBorder(): Province
    {
        return $this->border;
    }
}
