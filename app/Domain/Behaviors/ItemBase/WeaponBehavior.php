<?php


namespace App\Domain\Behaviors\ItemBase;


abstract class WeaponBehavior extends ItemBaseBehavior
{
    public function getItemGroup(): string
    {
        return 'weapon';
    }
}
