<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedHeroPostTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $heroRaces = \App\Domain\Models\HeroRace::all();

        $heroPostTypes = [
            [
                'name' => \App\HeroPostType::HUMAN,
                'races' => $heroRaces->where('name', '=', \App\Domain\Models\HeroRace::HUMAN)

            ],
            [
                'name' => \App\HeroPostType::ELF,
                'races' => $heroRaces->where('name', '=', \App\Domain\Models\HeroRace::ELF)

            ],
            [
                'name' => \App\HeroPostType::DWARF,
                'races' => $heroRaces->where('name', '=', \App\Domain\Models\HeroRace::DWARF)

            ],
            [
                'name' => \App\HeroPostType::ORC,
                'races' => $heroRaces->where('name', '=', \App\Domain\Models\HeroRace::ORC)

            ]
        ];

        foreach ($heroPostTypes as $postType) {
            /** @var \App\HeroPostType $createdHeroPostType */
            $createdHeroPostType = \App\HeroPostType::create([
                'name' => $postType['name']
            ]);

            $createdHeroPostType->heroRaces()->attach($postType['races']->pluck('id')->toArray());
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
