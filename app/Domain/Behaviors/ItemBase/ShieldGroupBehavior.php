<?php


namespace App\Domain\Behaviors\ItemBase;


abstract class ShieldGroupBehavior extends ItemBaseBehavior
{
    public function getItemGroup(): string
    {
        return 'shield';
    }
}
