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

    public function isLive()
    {
        $currentDayOfYear = CarbonImmutable::now()->dayOfYear;
        $yearStartDay = $this->getDayOfYearStart();
        $yearEndDate = $this->getDayOfYearEnd();
        if ( $yearEndDate < $yearStartDay ) {
            return $currentDayOfYear >= $yearStartDay || $currentDayOfYear <= $yearEndDate;
        } else {
            return $currentDayOfYear >= $yearStartDay && $currentDayOfYear <= $yearEndDate;
        }
    }
}