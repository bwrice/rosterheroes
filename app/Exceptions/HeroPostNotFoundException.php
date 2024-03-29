<?php

namespace App\Exceptions;

use App\Domain\Models\HeroRace;
use Exception;
use Throwable;

class HeroPostNotFoundException extends Exception
{
    /**
     * @var \App\Domain\Models\HeroRace
     */
    private $heroRace;

    public function __construct(HeroRace $heroRace, $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->heroRace = $heroRace;
        $message = $message ?: "No hero post found for hero race: " . $heroRace->name;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return \App\Domain\Models\HeroRace
     */
    public function getHeroRace(): HeroRace
    {
        return $this->heroRace;
    }
}
