<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:17 PM
 */

namespace App\Domain\Behaviors\ItemBase;


abstract class ItemBaseBehavior
{
    abstract public function getSlotsCount(): int;

    abstract public function getItemGroup(): string;
}
