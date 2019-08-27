<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedItemGroups extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\ItemGroup::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\ItemGroup::WEAPON,
            \App\Domain\Models\ItemGroup::ARMOR,
            \App\Domain\Models\ItemGroup::JEWELRY,
            \App\Domain\Models\ItemGroup::SHIELD
        ];
    }
}