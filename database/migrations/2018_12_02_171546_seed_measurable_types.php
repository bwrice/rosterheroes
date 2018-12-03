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

        $measurableGroups = \App\MeasurableGroup::all();
        $measurableTypes = [
            [
                'name' => \App\MeasurableType::STRENGTH,
                'group' => \App\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\MeasurableType::VALOR,
                'group' => \App\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\MeasurableType::AGILITY,
                'group' => \App\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\MeasurableType::FOCUS,
                'group' => \App\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\MeasurableType::APTITUDE,
                'group' => \App\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\MeasurableType::INTELLIGENCE,
                'group' => \App\MeasurableGroup::ATTRIBUTE
            ],
            [
                'name' => \App\MeasurableType::HEALTH,
                'group' => \App\MeasurableGroup::RESOURCE
            ],
            [
                'name' => \App\MeasurableType::STAMINA,
                'group' => \App\MeasurableGroup::RESOURCE
            ],
            [
                'name' => \App\MeasurableType::MANA,
                'group' => \App\MeasurableGroup::RESOURCE
            ],
            [
                'name' => \App\MeasurableType::PASSION,
                'group' => \App\MeasurableGroup::QUALITY
            ],
            [
                'name' => \App\MeasurableType::BALANCE,
                'group' => \App\MeasurableGroup::QUALITY
            ],
            [
                'name' => \App\MeasurableType::HONOR,
                'group' => \App\MeasurableGroup::QUALITY
            ],
            [
                'name' => \App\MeasurableType::PRESTIGE,
                'group' => \App\MeasurableGroup::QUALITY
            ],
            [
                'name' => \App\MeasurableType::WRATH,
                'group' => \App\MeasurableGroup::QUALITY
            ]
        ];

        foreach ($measurableTypes as $measurableType)
        {
            \App\MeasurableType::create([
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
        \App\MeasurableType::query()->delete();
    }
}
