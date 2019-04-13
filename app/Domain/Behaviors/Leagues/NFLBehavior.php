<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/11/19
 * Time: 10:11 PM
 */

namespace App\Domain\Behaviors\Leagues;


class NFLBehavior extends LeagueBehavior
{

    public function getDayOfYearStart(): int
    {
        return 240;
    }

    public function getDayOfYearEnd(): int
    {
        return 50;
    }
}