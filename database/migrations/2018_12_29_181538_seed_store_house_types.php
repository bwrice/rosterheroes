<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedStoreHouseTypes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\StoreHouseType::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\StoreHouseType::DEPOT
        ];
    }
}