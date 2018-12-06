<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/5/18
 * Time: 1:51 PM
 */

namespace App\Wagons\WagonSizes;


class MediumBehavior extends WagonSizeBehavior
{

    public function getTotalSlotsCount(): int
    {
        return 200;
    }
}