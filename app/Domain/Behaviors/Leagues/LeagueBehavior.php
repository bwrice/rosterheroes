<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/11/19
 * Time: 10:04 PM
 */

namespace App\Domain\Behaviors\Leagues;


use Carbon\CarbonImmutable;

class LeagueBehavior
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var int
     */
    private $dayOfYearStart;
    /**
     * @var int
     */
    private $dayOfYearEnd;
    /**
     * @var int
     */
    private $totalTeams;

    public function __construct(string $key, int $dayOfYearStart, int $dayOfYearEnd, int $totalTeams)
    {
        $this->key = $key;
        $this->dayOfYearStart = $dayOfYearStart;
        $this->dayOfYearEnd = $dayOfYearEnd;
        $this->totalTeams = $totalTeams;
    }

    public function isLive(): bool
    {
        $currentDayOfYear = CarbonImmutable::now()->dayOfYear;
        if ( $this->spansOverYearChange() ) {
            return $currentDayOfYear >= $this->getDayOfYearStart() || $currentDayOfYear <= $this->getDayOfYearEnd();
        } else {
            return $currentDayOfYear >= $this->getDayOfYearStart() && $currentDayOfYear <= $this->getDayOfYearEnd();
        }
    }

    public function getSeason(): int
    {
        $now = CarbonImmutable::now();
        if (! $this->isLive()) {
            return $now->year;
        }

        if ($this->spansOverYearChange() && ($now->dayOfYear <= $this->getDayOfYearEnd())) {
            return $now->year - 1;
        }
        return $now->year;
    }

    public function spansOverYearChange(): bool
    {
        return $this->getDayOfYearEnd() < $this->getDayOfYearStart();
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getDayOfYearStart(): int
    {
        return $this->dayOfYearStart;
    }

    /**
     * @return int
     */
    public function getDayOfYearEnd(): int
    {
        return $this->dayOfYearEnd;
    }

    /**
     * @return int
     */
    public function getTotalTeams(): int
    {
        return $this->totalTeams;
    }
}