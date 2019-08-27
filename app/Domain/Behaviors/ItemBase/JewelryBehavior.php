<?php


namespace App\Domain\Behaviors\ItemBase;


abstract class JewelryBehavior extends ItemBaseBehavior
{
    public function getGroupName(): string
    {
        return 'jewelry';
    }
}
