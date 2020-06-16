<?php

use App\Domain\Models\EnemyType;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedEnemyTypesA extends ModelNameSeederMigration
{

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return EnemyType::class;
    }

    /**
     * @return array
     */
    protected function getSeedNames(): array
    {
        return [
            EnemyType::IMP,
            EnemyType::TROLL
        ];
    }
}
