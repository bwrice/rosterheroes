<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:38 PM
 */

namespace App\External\Stats;


use App\Positions\PositionCollection;
use App\Team;

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
    private $integrationID;

    public function __construct(
        Team $team,
        PositionCollection $positions,
        string $firstName,
        string $lastName,
        string $integrationID)
    {

        $this->team = $team;
        $this->positions = $positions;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->integrationID = $integrationID;
    }

}