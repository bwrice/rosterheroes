<?php


namespace App\Exceptions;


use Throwable;

class NotEnoughGoldException extends \RuntimeException
{
    /**
     * @var int
     */
    private $available;
    /**
     * @var int
     */
    private $needed;

    public function __construct(int $available, int $needed, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $available . ' gold available, but ' . $needed . ' gold needed';
        $this->needed = $needed;
        $this->available = $available;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getAvailable(): int
    {
        return $this->available;
    }

    /**
     * @return int
     */
    public function getNeeded(): int
    {
        return $this->needed;
    }
}
