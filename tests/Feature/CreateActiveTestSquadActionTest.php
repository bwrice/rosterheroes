<?php

namespace Tests\Feature;

use App\Domain\Actions\Testing\AddTestHeroToTestSquadAction;
use App\Domain\Actions\Testing\CreateTestSquadAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use App\Jobs\AddTestHeroToTestSquadJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateActiveTestSquadActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
    * @test
    */
    public function it_will_create_squads_with_heroes_of_each_race_for_amount_given()
    {
        /** @var CreateTestSquadAction $domainAction */
        $domainAction = app(CreateTestSquadAction::class);
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

    /**
    * @test
    */
    public function it_will_dispatch_add_test_hero_jobs()
    {
        Queue::fake();

        /** @var CreateTestSquadAction $domainAction */
        $domainAction = app(CreateTestSquadAction::class);
        $squadsCount = 3;
        $heroRaces = HeroRace::starting()->get();
        $squads = $domainAction->execute($squadsCount);

        Queue::assertPushed(AddTestHeroToTestSquadJob::class, $squadsCount * $heroRaces->count());

        $squads->each(function (Squad $squad) use ($heroRaces) {
            $heroRaces->each(function (HeroRace $heroRace) use ($squad) {
                Queue::assertPushed(AddTestHeroToTestSquadJob::class, function (AddTestHeroToTestSquadJob $job) use ($squad, $heroRace) {
                    return ($squad->id === $job->squad->id) && ($heroRace->id === $job->heroRace->id);
                });
            });
        });
    }

}
