<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:26 PM
 */

namespace App\Domain\Behaviors\ItemBase;


class BootsBehavior extends ArmorBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
