<?php


namespace App\Domain\Behaviors\ItemBase;


abstract class ArmorBehavior extends ItemBaseBehavior
{
    public function getGroupName(): string
    {
        return 'armor';
    }
}
