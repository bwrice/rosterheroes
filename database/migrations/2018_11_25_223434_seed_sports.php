<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedSports extends ModelNameSeederMigration
{

    protected function getModelClass(): string
    {
        return \App\Sport::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Sport::FOOTBALL,
            \App\Sport::BASKETBALL,
            \App\Sport::BASEBALL,
            \App\Sport::HOCKEY
        ];
    }
}
