<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/11/19
 * Time: 10:25 PM
 */

namespace App\Domain\Behaviors\Leagues;


class MLBBehavior extends LeagueBehavior
{

    public function getDayOfYearStart(): int
    {
        return 80;
    }

    public function getDayOfYearEnd(): int
    {
        return 290;
    }
}