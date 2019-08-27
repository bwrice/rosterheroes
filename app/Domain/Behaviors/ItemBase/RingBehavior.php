<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:30 PM
 */

namespace App\Domain\Behaviors\ItemBase;


class RingBehavior extends JewelryBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
