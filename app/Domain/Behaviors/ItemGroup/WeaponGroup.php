<?php


namespace App\Domain\Behaviors\ItemGroup;


class WeaponGroup extends ItemGroup
{
    public function name(): string
    {
        return 'weapon';
    }
}
