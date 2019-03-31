<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedMaterialGroups extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\MaterialGroup::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\MaterialGroup::HIDE,
            \App\Domain\Models\MaterialGroup::METAL,
            \App\Domain\Models\MaterialGroup::CLOTH,
            \App\Domain\Models\MaterialGroup::WOOD,
            \App\Domain\Models\MaterialGroup::GEMSTONE,
            \App\Domain\Models\MaterialGroup::BONE,
            \App\Domain\Models\MaterialGroup::PRECIOUS_METAL,
            \App\Domain\Models\MaterialGroup::PSIONIC
        ];
    }
}