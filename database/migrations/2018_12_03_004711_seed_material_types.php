<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedMaterialTypes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\MaterialType::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\MaterialType::HIDE,
            \App\Domain\Models\MaterialType::METAL,
            \App\Domain\Models\MaterialType::CLOTH,
            \App\Domain\Models\MaterialType::WOOD,
            \App\Domain\Models\MaterialType::GEMSTONE,
            \App\Domain\Models\MaterialType::BONE,
            \App\Domain\Models\MaterialType::PRECIOUS_METAL,
            \App\Domain\Models\MaterialType::PSIONIC
        ];
    }
}
