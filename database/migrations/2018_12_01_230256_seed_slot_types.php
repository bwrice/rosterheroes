<?php

use App\Domain\Models\SlotType;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedSlotTypes extends ModelNameSeederMigration
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return SlotType::class;
    }

    /**
     * @return array
     */
    protected function getSeedNames(): array
    {
        return [
            SlotType::RIGHT_ARM,
            SlotType::LEFT_ARM,
            SlotType::HEAD,
            SlotType::TORSO,
            SlotType::LEGS,
            SlotType::HANDS,
            SlotType::FEET,
            SlotType::WAIST,
            SlotType::NECK,
            SlotType::RIGHT_WRIST,
            SlotType::LEFT_WRIST,
            SlotType::RIGHT_RING,
            SlotType::LEFT_RING,
            SlotType::UNIVERSAL
        ];
    }
}
