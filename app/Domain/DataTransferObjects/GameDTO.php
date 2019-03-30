<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/28/19
 * Time: 9:23 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Teams\Team;
use App\Weeks\Week;
use Carbon\Carbon;

class GameDTO
{
    /**
     * @var Week
     */
    private $week;
    /**
     * @var Team
     */
    private $homeTeam;
    /**
     * @var Team
     */
    private $awayTeam;
    /**
     * @var Carbon
     */
    private $startsAt;
    /**
     * @var string
     */
    private $externalID;

    public function __construct(Week $week, Team $homeTeam, Team $awayTeam, Carbon $startsAt, string $externalID)
    {
        $this->week = $week;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->startsAt = $startsAt;
        $this->externalID = $externalID;
    }

    /**
     * @return Week
     */
    public function getWeek(): Week
    {
        return $this->week;
    }

    /**
     * @return Team
     */
    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    /**
     * @return Team
     */
    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    /**
     * @return Carbon
     */
    public function getStartsAt(): Carbon
    {
        return $this->startsAt;
    }

    /**
     * @return string
     */
    public function getExternalID(): string
    {
        return $this->externalID;
    }
}