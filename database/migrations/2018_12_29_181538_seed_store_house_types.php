<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedStoreHouseTypes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\StoreHouseType::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\StoreHouseType::DEPOT
        ];
    }
}