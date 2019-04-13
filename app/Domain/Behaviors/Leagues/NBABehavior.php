<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/13/19
 * Time: 10:11 AM
 */

namespace App\Domain\Behaviors\Leagues;


class NBABehavior extends LeagueBehavior
{

    public function getDayOfYearStart(): int
    {
        return 275;
    }

    public function getDayOfYearEnd(): int
    {
        return 180;
    }
}