<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:38 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Team;

class PlayerDTO
{
    /**
     * @var Team
     */
    private $team;
    /**
     * @var PositionCollection
     */
    private $positions;
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;
    /**
     * @var string
     */
    private $externalID;

    public function __construct(
        Team $team,
        PositionCollection $positions,
        string $firstName,
        string $lastName,
        string $externalID)
    {

        $this->team = $team;
        $this->positions = $positions;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->externalID = $externalID;
    }

    /**
     * @return Team
     */
    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * @return PositionCollection
     */
    public function getPositions(): PositionCollection
    {
        return $this->positions;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getExternalID(): string
    {
        return $this->externalID;
    }

}