<?php

use App\Domain\Models\CombatPosition;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedCombatPosition extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return CombatPosition::class;
    }

    public function getSeedNames(): array
    {
        return [
            CombatPosition::MELEE,
            CombatPosition::MID_RANGE,
            CombatPosition::LONG_RANGE
        ];
    }
}
