<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedItemClasses extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\ItemClass::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\ItemClass::GENERIC,
            \App\Domain\Models\ItemClass::ENCHANTED,
            \App\Domain\Models\ItemClass::LEGENDARY,
            \App\Domain\Models\ItemClass::MYTHICAL
        ];
    }
}