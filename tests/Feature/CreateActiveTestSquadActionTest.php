<?php

namespace Tests\Feature;

use App\Domain\Actions\CreateActiveTestSquadAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateActiveTestSquadActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
    * @test
    */
    public function it_will_create_squads_with_heroes_of_each_race_for_amount_given()
    {
        /** @var CreateActiveTestSquadAction $domainAction */
        $domainAction = app(CreateActiveTestSquadAction::class);
        $count = 3;
        $squads = $domainAction->execute($count);
        $this->assertEquals($count, $squads->count());

        $heroRaces = HeroRace::all();

        $squads->load('heroes')->each(function (Squad $squad) use ($heroRaces) {
            $heroRaces->each(function (HeroRace $heroRace) use ($squad) {
                $match = $squad->heroes->first(function (Hero $hero) use ($heroRace) {
                    return $hero->hero_race_id === $heroRace->id;
                });
                $this->assertNotNull($match, $heroRace->name . " hero not found");
            });
        });
    }

}
