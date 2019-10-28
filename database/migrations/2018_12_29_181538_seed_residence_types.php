<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedResidenceTypes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\ResidenceType::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\ResidenceType::SHACK
        ];
    }
}
