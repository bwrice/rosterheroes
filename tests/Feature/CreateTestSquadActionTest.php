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

class CreateTestSquadActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
    * @test
    */
    public function it_will_create_squads_for_the_amount_given()
    {
        Queue::fake();
        /** @var CreateTestSquadAction $domainAction */
        $domainAction = app(CreateTestSquadAction::class);
        $count = 2;
        $squads = $domainAction->execute($count);
        $this->assertEquals($count, $squads->count());
    }

    /**
    * @test
    */
    public function it_will_give_the_squad_and_user_test_names()
    {
        Queue::fake();
        /** @var CreateTestSquadAction $domainAction */
        $domainAction = app(CreateTestSquadAction::class);
        $squads = $domainAction->execute(1);
        /** @var Squad $squad */
        $squad = $squads->first();
        $this->assertTrue(strpos($squad->name, "TestSquad") === 0);
        $this->assertTrue(strpos($squad->user->name, "TestUser") === 0);
    }

    /**
    * @test
    */
    public function it_will_dispatch_add_test_hero_jobs()
    {
        Queue::fake();

        /** @var CreateTestSquadAction $domainAction */
        $domainAction = app(CreateTestSquadAction::class);
        $squadsCount = 2;
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
