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
        $measurableTypes = \App\MeasurableType::all();

        $measurableTypesArray = [
            \App\MeasurableType::STRENGTH,
            \App\MeasurableType::VALOR,
            \App\MeasurableType::AGILITY,
            \App\MeasurableType::FOCUS,
            \App\MeasurableType::APTITUDE,
            \App\MeasurableType::INTELLIGENCE,
            \App\MeasurableType::HEALTH,
            \App\MeasurableType::STAMINA,
            \App\MeasurableType::MANA,
            \App\MeasurableType::PASSION,
            \App\MeasurableType::BALANCE,
            \App\MeasurableType::HONOR,
            \App\MeasurableType::PRESTIGE,
            \App\MeasurableType::WRATH
        ];

        foreach ( $measurableTypesArray as $type ) {
            foreach( range( 1, 50 ) as $boostLevel ) {

                $name = 'Level ' . $boostLevel . ' ' . ucfirst( $type );

                /** @var \App\Enchantment $enchantment */
                $enchantment = \App\Enchantment::create( [
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

        \App\Enchantment::all()->each(function(\App\Enchantment $enchantment) {
            $enchantment->measurableBoosts()->delete();
            $enchantment->delete();
        });
    }
}
