<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/11/19
 * Time: 10:04 PM
 */

namespace App\Domain\Behaviors\Leagues;

use Carbon\CarbonInterface;

class LeagueBehavior
{
    /**
     * @var int
     */
    protected $seasonChangeOverDayOfYear = 1;

    public function getSeason(CarbonInterface $date = null): int
    {
        $date = $date ?: now();
        if ($date->dayOfYear >= $this->seasonChangeOverDayOfYear) {
            return $date->year;
        }

        return $date->year - 1;
    }
}
