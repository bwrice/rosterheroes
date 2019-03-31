<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/28/19
 * Time: 9:23 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Models\Team;
use Carbon\Carbon;

class GameDTO
{
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

    public function __construct(Carbon $startsAt, Team $homeTeam, Team $awayTeam, string $externalID)
    {
        $this->startsAt = $startsAt;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->externalID = $externalID;
    }

    /**
     * @return \App\Domain\Models\Team
     */
    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    /**
     * @return \App\Domain\Models\Team
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