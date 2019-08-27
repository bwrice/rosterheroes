<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:24 PM
 */

namespace App\Domain\Behaviors\ItemBase;


class ShieldBehavior extends ShieldGroupBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
