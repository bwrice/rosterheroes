<?php

use App\Domain\Models\EnemyType;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedEnemyTypes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return EnemyType::class;
    }

    public function getSeedNames(): array
    {
        return [
            EnemyType::GIANT,
            EnemyType::GARGOYLE,
            EnemyType::GOLEM,
            EnemyType::UNDEAD,
            EnemyType::VAMPIRE,
            EnemyType::WEREWOLF,
            EnemyType::WITCH
        ];
    }
}
