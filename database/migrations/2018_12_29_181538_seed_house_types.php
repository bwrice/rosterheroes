<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedHouseTypes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\HouseType::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\HouseType::SHACK
        ];
    }
}
