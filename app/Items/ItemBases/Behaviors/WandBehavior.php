<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:22 PM
 */

namespace App\Items\ItemBases\Behaviors;


class WandBehavior extends ItemBaseBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}