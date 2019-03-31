<?php

namespace App\Exceptions;

use App\Domain\Models\HeroClass;
use Exception;
use Throwable;

class InvalidHeroClassException extends Exception
{
    /**
     * @var \App\Domain\Models\HeroClass
     */
    private $heroClass;

    public function __construct(HeroClass $heroClass, $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $heroClass->name . " is an invalid Hero Class";
        parent::__construct($message, $code, $previous);
        $this->heroClass = $heroClass;
    }

    /**
     * @return HeroClass
     */
    public function getHeroClass(): HeroClass
    {
        return $this->heroClass;
    }
}
