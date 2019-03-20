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
                'name' => 'National Football League',
                'abbreviation' => \App\League::NFL,
                'sport' => \App\Sport::where('name', '=', \App\Sport::FOOTBALL)->first()
            ],
            [
                'name' => 'Major League Baseball',
                'abbreviation' => \App\League::MLB,
                'sport' => \App\Sport::where('name', '=', \App\Sport::BASEBALL)->first()
            ],
            [
                'name' => 'National Basketball Association',
                'abbreviation' => \App\League::NBA,
                'sport' => \App\Sport::where('name', '=', \App\Sport::BASKETBALL)->first()
            ],
            [
                'name' => 'National Hockey League',
                'abbreviation' => \App\League::NHL,
                'sport' => \App\Sport::where('name', '=', \App\Sport::HOCKEY)->first()
            ],
        ];

        foreach ($leagues as $league)
        {
            \App\League::create([
                'name' => $league['name'],
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
        \App\League::truncate();
    }
}
