<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:25 PM
 */

namespace App\Items\ItemBases\Behaviors;


class CapBehavior extends ItemBaseBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}