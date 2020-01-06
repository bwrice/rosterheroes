<?php


namespace App\Exceptions;


use App\Domain\Models\Week;
use Throwable;

class FinalizeWeekException extends \RuntimeException
{
    public const INVALID_TIME_TO_FINALIZE = 1;
    public const CODE_GAMES_NOT_FINALIZED = 2;

    /**
     * @var Week
     */
    private $week;

    public function __construct(Week $week, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->week = $week;
    }

    /**
     * @return Week
     */
    public function getWeek(): Week
    {
        return $this->week;
    }
}
