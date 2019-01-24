<?php

namespace App\Exceptions;

use App\Continent;
use Exception;
use Throwable;

class CampaignFoundForOtherContinentException extends \RuntimeException
{
    /**
     * @var Continent
     */
    private $existingContinent;
    /**
     * @var Continent
     */
    private $wantedContinent;

    public function __construct(Continent $existingContinent, Continent $wantedContinent, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->existingContinent = $existingContinent;
        $this->wantedContinent = $wantedContinent;

        $message = $message ?: "Campaign already exists for continent " . $existingContinent->name . " when trying to get campaign for continent " . $wantedContinent->name;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Continent
     */
    public function getExistingContinent(): Continent
    {
        return $this->existingContinent;
    }

    /**
     * @return Continent
     */
    public function getWantedContinent(): Continent
    {
        return $this->wantedContinent;
    }
}
