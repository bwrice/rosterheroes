<?php


namespace App\Domain\Behaviors\ItemBase;


abstract class JewelryBehavior extends ItemBaseBehavior
{
    public function getItemGroup(): string
    {
        return 'jewelry';
    }
}
