<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedContinents extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\Continent::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\Continent::FETROYA,
            \App\Domain\Models\Continent::EAST_WOZUL,
            \App\Domain\Models\Continent::WEST_WOZUL,
            \App\Domain\Models\Continent::NORTH_JAGONETH,
            \App\Domain\Models\Continent::CENTRAL_JAGONETH,
            \App\Domain\Models\Continent::SOUTH_JAGONETH,
            \App\Domain\Models\Continent::VINDOBERON,
            \App\Domain\Models\Continent::DEMAUXOR
        ];
    }
}