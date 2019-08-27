<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:27 PM
 */

namespace App\Domain\Behaviors\ItemBase;


class SashBehavior extends ClothingBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
