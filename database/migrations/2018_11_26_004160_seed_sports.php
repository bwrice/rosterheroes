<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedSports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sports = [
            [
                'name' => 'Football',
                'abbreviation' => 'NFL'
            ],
            [
                'name' => 'Baseball',
                'abbreviation' => 'MLB'
            ],
            [
                'name' => 'Basketball',
                'abbreviation' => 'NBA'
            ],
            [
                'name' => 'Hockey',
                'abbreviation' => 'NHL'
            ],
        ];

        \App\Sport::unguard();

        foreach ($sports as $sport)
        {
            \App\Sport::create([
                'name' => $sport['name'],
                'abbreviation' => $sport['abbreviation']
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
        \App\Sport::truncate();
    }
}
