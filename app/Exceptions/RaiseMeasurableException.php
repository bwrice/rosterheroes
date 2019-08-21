<?php


namespace App\Exceptions;


use App\Domain\Models\Measurable;
use Throwable;

class RaiseMeasurableException extends \Exception
{
    public const CODE_NON_POSITIVE_NUMBER = 1;
    public const INSUFFICIENT_EXPERIENCE = 2;

    /**
     * @var Measurable
     */
    private $measurable;
    /**
     * @var int
     */
    private $amount;

    public function __construct(Measurable $measurable, int $amount, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->measurable = $measurable;
        $this->amount = $amount;
    }

    /**
     * @return Measurable
     */
    public function getMeasurable(): Measurable
    {
        return $this->measurable;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}
