<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedItemGroups extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\ItemGroup::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\ItemGroup::WEAPON,
            \App\ItemGroup::ARMOR,
            \App\ItemGroup::JEWELRY,
            \App\ItemGroup::SHIELD
        ];
    }
}