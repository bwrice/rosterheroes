<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:23 PM
 */

namespace App\Items\ItemBases\Behaviors;


class PsionicTwoHandBehavior extends ItemBaseBehavior
{

    public function getSlotsCount(): int
    {
        return 2;
    }
}