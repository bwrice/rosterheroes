<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/5/18
 * Time: 1:50 PM
 */

namespace App\Wagons\WagonSizes;


class SmallBehavior extends WagonSizeBehavior
{

    public function getTotalSlotsCount(): int
    {
        return 80;
    }
}