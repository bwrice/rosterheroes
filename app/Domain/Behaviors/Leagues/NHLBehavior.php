<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/13/19
 * Time: 10:21 AM
 */

namespace App\Domain\Behaviors\Leagues;


class NHLBehavior extends LeagueBehavior
{

    public function getDayOfYearStart(): int
    {
        return 265;
    }

    public function getDayOfYearEnd(): int
    {
        return 170;
    }
}