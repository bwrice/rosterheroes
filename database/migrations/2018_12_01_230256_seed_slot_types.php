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
            SlotType::PRIMARY_ARM,
            SlotType::OFF_ARM,
            SlotType::HEAD,
            SlotType::TORSO,
            SlotType::LEGS,
            SlotType::HANDS,
            SlotType::FEET,
            SlotType::WAIST,
            SlotType::NECK,
            SlotType::PRIMARY_WRIST,
            SlotType::OFF_WRIST,
            SlotType::RING_ONE,
            SlotType::RING_TWO,
            SlotType::UNIVERSAL
        ];
    }
}
