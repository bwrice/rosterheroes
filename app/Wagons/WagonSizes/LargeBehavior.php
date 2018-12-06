<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/5/18
 * Time: 1:51 PM
 */

namespace App\Wagons\WagonSizes;


class LargeBehavior extends WagonSizeBehavior
{

    public function getTotalSlotsCount(): int
    {
        return 500;
    }
}