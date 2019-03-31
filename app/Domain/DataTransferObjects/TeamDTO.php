<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/16/19
 * Time: 1:37 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Models\League;

class TeamDTO
{
    /**
     * @var \App\Domain\Models\League
     */
    private $league;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $location;
    /**
     * @var string
     */
    private $abbreviation;
    /**
     * @var string
     */
    private $externalID;

    public function __construct(League $league, string $name, string $location, string $abbreviation, string $externalID)
    {
        $this->league = $league;
        $this->name = $name;
        $this->location = $location;
        $this->abbreviation = $abbreviation;
        $this->externalID = $externalID;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getExternalID(): string
    {
        return $this->externalID;
    }
}