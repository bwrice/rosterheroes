<?php

use App\Domain\Models\Enchantment;
use App\Domain\Models\MeasurableType;
use Illuminate\Support\Collection;
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
        $measurableTypes = MeasurableType::all();

        $measurableTypesArray = [
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
            MeasurableType::WRATH
        ];

        foreach ($measurableTypesArray as $type) {
            foreach(range( 1, 50) as $boostLevel) {

                $name = 'Level ' . $boostLevel . ' ' . ucfirst($type);

                /** @var Enchantment $enchantment */
                $enchantment = Enchantment::create([
                    'name' => $name
                ]);

                $enchantment->measurableBoosts()->create([
                    'measurable_type_id' => $measurableTypes->where('name', $type)->first()->id,
                    'boost_level' => $boostLevel
                ]);
            }
        }

        $this->createBeginnersBlessing($measurableTypes);
    }

    protected function createBeginnersBlessing(Collection $measurableTypes)
    {
        /** @var Enchantment $enchantment */
        $enchantment = Enchantment::create([
            'name' => "Beginner's Blessing"
        ]);

        $qualities = [
            MeasurableType::PASSION,
            MeasurableType::BALANCE,
            MeasurableType::HONOR,
            MeasurableType::PRESTIGE,
            MeasurableType::WRATH
        ];

        foreach ($qualities as $quality) {
            $enchantment->measurableBoosts()->create([
                'measurable_type_id' => $measurableTypes->where('name', $quality)->first()->id,
                'boost_level' => 1
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
