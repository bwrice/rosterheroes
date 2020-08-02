<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/28/19
 * Time: 9:23 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Models\Game;
use App\Domain\Models\Team;
use Carbon\CarbonInterface;

class GameDTO
{

    /**
     * @var Team
     */
    protected $homeTeam;
    /**
     * @var Team
     */
    protected $awayTeam;
    /**
     * @var CarbonInterface
     */
    protected $startsAt;
    /**
     * @var string
     */
    protected $externalID;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $seasonType;

    public function __construct(
        CarbonInterface $startsAt,
        Team $homeTeam,
        Team $awayTeam,
        string $externalID,
        string $status,
        string $seasonType = Game::SEASON_TYPE_REGULAR)
    {
        $this->startsAt = $startsAt;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->externalID = $externalID;
        $this->status = $status;
        $this->seasonType = $seasonType;
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
     * @return CarbonInterface
     */
    public function getStartsAt(): CarbonInterface
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

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getSeasonType(): string
    {
        return $this->seasonType;
    }
}
