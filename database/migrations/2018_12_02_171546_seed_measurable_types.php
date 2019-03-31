<?php

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

        $measurableGroups = \App\Domain\Models\MeasurableGroup::all();
        $measurableTypes = [
            [
                'name' => \App\Domain\Models\MeasurableType::STRENGTH,
                'group' => \App\Domain\Models\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::VALOR,
                'group' => \App\Domain\Models\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::AGILITY,
                'group' => \App\Domain\Models\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::FOCUS,
                'group' => \App\Domain\Models\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::APTITUDE,
                'group' => \App\Domain\Models\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::INTELLIGENCE,
                'group' => \App\Domain\Models\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::HEALTH,
                'group' => \App\Domain\Models\MeasurableGroup::RESOURCE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::STAMINA,
                'group' => \App\Domain\Models\MeasurableGroup::RESOURCE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::MANA,
                'group' => \App\Domain\Models\MeasurableGroup::RESOURCE
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::PASSION,
                'group' => \App\Domain\Models\MeasurableGroup::QUALITY
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::BALANCE,
                'group' => \App\Domain\Models\MeasurableGroup::QUALITY
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::HONOR,
                'group' => \App\Domain\Models\MeasurableGroup::QUALITY
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::PRESTIGE,
                'group' => \App\Domain\Models\MeasurableGroup::QUALITY
            ],
            [
                'name' => \App\Domain\Models\MeasurableType::WRATH,
                'group' => \App\Domain\Models\MeasurableGroup::QUALITY
            ]
        ];

        foreach ($measurableTypes as $measurableType)
        {
            \App\Domain\Models\MeasurableType::create([
                'name' => $measurableType['name'],
                'measurable_group_id' => $measurableGroups->where('name', $measurableType['group'])->first()->id
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
        \App\Domain\Models\MeasurableType::query()->delete();
    }
}
