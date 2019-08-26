<?php

use App\Domain\Models\SlotType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedSlotTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $slotTypes = [
            [
                'name' => SlotType::RIGHT_ARM
            ],
            [
                'name' => SlotType::LEFT_ARM
            ],
            [
                'name' => SlotType::TORSO
            ],
            [
                'name' => SlotType::HEAD
            ],
            [
                'name' => SlotType::HANDS
            ],
            [
                'name' => SlotType::FEET,
            ],
            [
                'name' => SlotType::WAIST
            ],
            [
                'name' => SlotType::NECK,
            ],
            [
                'name' => SlotType::RIGHT_WRIST
            ],
            [
                'name' => SlotType::LEFT_WRIST
            ],
            [
                'name' => SlotType::RIGHT_RING
            ],
            [
                'name' => SlotType::LEFT_RING
            ],
            [
                'name' => SlotType::UNIVERSAL
        ];

        foreach ($slotTypes as $slotType) {
            $slotTypeCreated = SlotType::query()->create([
                'name' => $slotType['name'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
