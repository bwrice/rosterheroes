<?php

use App\Domain\Models\DamageType;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedDamageTypes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return DamageType::class;
    }

    public function getSeedNames(): array
    {
        return [
            DamageType::FIXED_TARGET,
            DamageType::AREA_OF_EFFECT,
            DamageType::DISPERSED
        ];
    }
}
