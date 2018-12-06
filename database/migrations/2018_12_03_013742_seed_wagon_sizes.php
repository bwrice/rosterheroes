<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedWagonSizes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Wagons\WagonSizes\WagonSize::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Wagons\WagonSizes\WagonSize::SMALL,
            \App\Wagons\WagonSizes\WagonSize::MEDIUM,
            \App\Wagons\WagonSizes\WagonSize::LARGE
        ];
    }
}