<?php


namespace App\Domain\Behaviors\ItemBase;


abstract class ClothingBehavior extends ItemBaseBehavior
{
    public function getGroupName(): string
    {
        return 'clothing';
    }
}
