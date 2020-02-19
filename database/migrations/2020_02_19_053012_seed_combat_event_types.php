<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedCombatEventTypes extends ModelNameSeederMigration
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return \App\CombatEventType::class;
    }

    /**
     * @return array
     */
    protected function getSeedNames(): array
    {
        return [
            \App\CombatEventType::ATTACK,
            \App\CombatEventType::BLOCK,
            \App\CombatEventType::KILL,
        ];
    }
}
