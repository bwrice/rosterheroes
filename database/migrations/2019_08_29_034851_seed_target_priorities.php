<?php

use App\Domain\Models\TargetPriority;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedTargetPriorities extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return TargetPriority::class;
    }

    public function getSeedNames(): array
    {
        return [
            TargetPriority::ANY,
            TargetPriority::LOWEST_HEALTH,
            TargetPriority::HIGHEST_THREAT
        ];
    }
}
