<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedContinents extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Continent::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Continent::FETROYA,
            \App\Continent::EAST_WOZUL,
            \App\Continent::WEST_WOZUL,
            \App\Continent::NORTH_JAGONETH,
            \App\Continent::CENTRAL_JAGONETH,
            \App\Continent::SOUTH_JAGONETH,
            \App\Continent::VINDOBERON,
            \App\Continent::DEMAUXOR
        ];
    }
}