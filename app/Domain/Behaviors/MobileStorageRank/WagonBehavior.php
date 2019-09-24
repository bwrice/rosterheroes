<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/3/19
 * Time: 10:09 PM
 */

namespace App\Domain\Behaviors\MobileStorageRank;


class WagonBehavior extends MobileStorageRankBehavior
{

    public function getSlotsCount()
    {
        return 50;
    }
}
