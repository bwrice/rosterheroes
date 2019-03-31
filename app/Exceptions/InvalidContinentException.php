<?php

namespace App\Exceptions;

use App\Domain\Models\Continent;
use Exception;
use Throwable;

class InvalidContinentException extends \RuntimeException
{
    /**
     * @var \App\Domain\Models\Continent
     */
    private $invalidContinent;

    public function __construct(Continent $invalidContinent, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->invalidContinent = $invalidContinent;

        $message = $message ?: "Continent: " . $invalidContinent->name . " is not valid";
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return \App\Domain\Models\Continent
     */
    public function getInvalidContinent(): Continent
    {
        return $this->invalidContinent;
    }
}
