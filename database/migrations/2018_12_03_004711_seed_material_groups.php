<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedMaterialGroups extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\MaterialGroup::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\MaterialGroup::HIDE,
            \App\MaterialGroup::METAL,
            \App\MaterialGroup::CLOTH,
            \App\MaterialGroup::WOOD,
            \App\MaterialGroup::GEMSTONE,
            \App\MaterialGroup::BONE,
            \App\MaterialGroup::PRECIOUS_METAL,
            \App\MaterialGroup::PSIONIC
        ];
    }
}