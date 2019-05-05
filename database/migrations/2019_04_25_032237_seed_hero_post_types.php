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
                'name' => \App\Domain\Models\HeroPostType::HUMAN,
                'races' => $heroRaces->where('name', '=', \App\Domain\Models\HeroRace::HUMAN)

            ],
            [
                'name' => \App\Domain\Models\HeroPostType::ELF,
                'races' => $heroRaces->where('name', '=', \App\Domain\Models\HeroRace::ELF)

            ],
            [
                'name' => \App\Domain\Models\HeroPostType::DWARF,
                'races' => $heroRaces->where('name', '=', \App\Domain\Models\HeroRace::DWARF)

            ],
            [
                'name' => \App\Domain\Models\HeroPostType::ORC,
                'races' => $heroRaces->where('name', '=', \App\Domain\Models\HeroRace::ORC)

            ]
        ];

        foreach ($heroPostTypes as $postType) {
            /** @var \App\Domain\Models\HeroPostType $createdHeroPostType */
            $createdHeroPostType = \App\Domain\Models\HeroPostType::create([
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
