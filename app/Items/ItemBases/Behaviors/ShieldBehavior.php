<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:24 PM
 */

namespace App\Items\ItemBases\Behaviors;


class ShieldBehavior extends ItemBaseBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}