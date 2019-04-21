<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/11/19
 * Time: 10:04 PM
 */

namespace App\Domain\Behaviors\Leagues;


use Carbon\CarbonImmutable;

abstract class LeagueBehavior
{
    abstract public function getDayOfYearStart(): int;

    abstract public function getDayOfYearEnd(): int;

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
}