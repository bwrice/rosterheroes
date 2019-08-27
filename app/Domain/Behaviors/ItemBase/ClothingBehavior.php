<?php


namespace App\Domain\Behaviors\ItemBase;


abstract class ClothingBehavior extends ItemBaseBehavior
{
    public function getItemGroup(): string
    {
        return 'clothing';
    }
}
