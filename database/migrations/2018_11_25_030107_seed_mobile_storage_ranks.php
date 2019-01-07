<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedMobileStorageRanks extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Squads\MobileStorage\MobileStorageRank::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Squads\MobileStorage\MobileStorageRank::WAGON
        ];
    }
}