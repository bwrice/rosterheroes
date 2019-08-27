<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:25 PM
 */

namespace App\Domain\Behaviors\ItemBase;


class EyeWearBehavior extends ItemBaseBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getItemGroup(): string
    {
        return 'eye-wear';
    }
}
