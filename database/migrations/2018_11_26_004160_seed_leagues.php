<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedLeagues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $leagues = [
            [
                'abbreviation' => \App\Domain\Models\League::NFL,
                'sport' => \App\Domain\Models\Sport::where('name', '=', \App\Domain\Models\Sport::FOOTBALL)->first()
            ],
            [
                'abbreviation' => \App\Domain\Models\League::MLB,
                'sport' => \App\Domain\Models\Sport::where('name', '=', \App\Domain\Models\Sport::BASEBALL)->first()
            ],
            [
                'abbreviation' => \App\Domain\Models\League::NBA,
                'sport' => \App\Domain\Models\Sport::where('name', '=', \App\Domain\Models\Sport::BASKETBALL)->first()
            ],
            [
                'abbreviation' => \App\Domain\Models\League::NHL,
                'sport' => \App\Domain\Models\Sport::where('name', '=', \App\Domain\Models\Sport::HOCKEY)->first()
            ],
        ];

        foreach ($leagues as $league)
        {
            \App\Domain\Models\League::create([
                'sport_id' => $league['sport']->id,
                'abbreviation' => $league['abbreviation']
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
        \App\Domain\Models\League::truncate();
    }
}
