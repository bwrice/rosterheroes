<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/28/19
 * Time: 9:23 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Models\Team;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class GameDTO
{
    public const SCHEDULE_STATUS_DELAYED = 'DELAYED';
    public const SCHEDULE_STATUS_NORMAL = 'NORMAL';

    /**
     * @var Team
     */
    private $homeTeam;
    /**
     * @var Team
     */
    private $awayTeam;
    /**
     * @var CarbonInterface
     */
    private $startsAt;
    /**
     * @var string
     */
    private $externalID;
    /**
     * @var string
     */
    private $status;

    public function __construct(
        CarbonInterface $startsAt,
        Team $homeTeam,
        Team $awayTeam,
        string $externalID,
        string $status)
    {
        $this->startsAt = $startsAt;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->externalID = $externalID;
        $this->status = $status;
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
}
