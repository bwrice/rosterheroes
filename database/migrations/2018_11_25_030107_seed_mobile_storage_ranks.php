<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedMobileStorageRanks extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\MobileStorageRank::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\MobileStorageRank::WAGON
        ];
    }
}