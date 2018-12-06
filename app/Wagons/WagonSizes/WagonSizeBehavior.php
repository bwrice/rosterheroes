<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/5/18
 * Time: 1:50 PM
 */

namespace App\Wagons\WagonSizes;


abstract class WagonSizeBehavior
{
    abstract public function getTotalSlotsCount(): int;
}