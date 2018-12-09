<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedItemClasses extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\ItemClass::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\ItemClass::GENERIC,
            \App\ItemClass::ENCHANTED,
            \App\ItemClass::LEGENDARY,
            \App\ItemClass::MYTHICAL
        ];
    }
}