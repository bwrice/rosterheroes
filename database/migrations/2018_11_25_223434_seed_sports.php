<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedSports extends ModelNameSeederMigration
{

    protected function getModelClass(): string
    {
        return \App\Domain\Models\Sport::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\Sport::FOOTBALL,
            \App\Domain\Models\Sport::BASKETBALL,
            \App\Domain\Models\Sport::BASEBALL,
            \App\Domain\Models\Sport::HOCKEY
        ];
    }
}
