<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedWagonSizes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\WagonSize::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\WagonSize::SMALL,
            \App\WagonSize::MEDIUM,
            \App\WagonSize::LARGE
        ];
    }
}