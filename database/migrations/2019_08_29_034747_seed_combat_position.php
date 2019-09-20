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
            CombatPosition::FRONT_LINE,
            CombatPosition::BACK_LINE,
            CombatPosition::HIGH_GROUND
        ];
    }
}
