<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedEnchantments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $measurableTypes = \App\Domain\Models\MeasurableType::all();

        $measurableTypesArray = [
            \App\Domain\Models\MeasurableType::STRENGTH,
            \App\Domain\Models\MeasurableType::VALOR,
            \App\Domain\Models\MeasurableType::AGILITY,
            \App\Domain\Models\MeasurableType::FOCUS,
            \App\Domain\Models\MeasurableType::APTITUDE,
            \App\Domain\Models\MeasurableType::INTELLIGENCE,
            \App\Domain\Models\MeasurableType::HEALTH,
            \App\Domain\Models\MeasurableType::STAMINA,
            \App\Domain\Models\MeasurableType::MANA,
            \App\Domain\Models\MeasurableType::PASSION,
            \App\Domain\Models\MeasurableType::BALANCE,
            \App\Domain\Models\MeasurableType::HONOR,
            \App\Domain\Models\MeasurableType::PRESTIGE,
            \App\Domain\Models\MeasurableType::WRATH
        ];

        foreach ( $measurableTypesArray as $type ) {
            foreach( range( 1, 50 ) as $boostLevel ) {

                $name = 'Level ' . $boostLevel . ' ' . ucfirst( $type );

                /** @var \App\Domain\Models\Enchantment $enchantment */
                $enchantment = \App\Domain\Models\Enchantment::create( [
                    'name' => $name
                ] );

                $enchantment->measurableBoosts()->create([
                    'measurable_type_id' => $measurableTypes->where('name', $type )->first()->id,
                    'boost_level' => $boostLevel
                ]);
            }
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
