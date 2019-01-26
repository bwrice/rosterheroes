<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class MaxQuestsException extends Exception
{
    /**
     * @var int
     */
    private $maxAmount;

    public function __construct(int $maxAmount, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: "Max amount of " . $maxAmount . " quests already reached";
        parent::__construct($message, $code, $previous);
        $this->maxAmount = $maxAmount;
    }

    /**
     * @return int
     */
    public function getMaxAmount(): int
    {
        return $this->maxAmount;
    }
}
