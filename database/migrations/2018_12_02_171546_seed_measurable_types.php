<?php

use App\Domain\Models\MeasurableType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedMeasurableTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $measurableTypes = [
                MeasurableType::STRENGTH,
                MeasurableType::VALOR,
                MeasurableType::AGILITY,
                MeasurableType::FOCUS,
                MeasurableType::APTITUDE,
                MeasurableType::INTELLIGENCE,
                MeasurableType::HEALTH,
                MeasurableType::STAMINA,
                MeasurableType::MANA,
                MeasurableType::PASSION,
                MeasurableType::BALANCE,
                MeasurableType::HONOR,
                MeasurableType::PRESTIGE,
                MeasurableType::WRATH,
        ];

        foreach ($measurableTypes as $measurableType)
        {
            MeasurableType::create([
                'name' => $measurableType
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
        MeasurableType::query()->delete();
    }
}
