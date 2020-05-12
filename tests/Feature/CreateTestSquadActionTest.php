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
    public function it_will_give_the_squad_and_user_test_names()
    {
        Queue::fake();
        /** @var CreateTestSquadAction $domainAction */
        $domainAction = app(CreateTestSquadAction::class);
        $testID = rand(9999, 999999);
        $squad = $domainAction->execute($testID);
        $this->assertTrue(strpos($squad->name, "TestSquad") === 0);
        $this->assertTrue(strpos($squad->user->name, "TestUser") === 0);
    }

    /**
    * @test
    */
    public function it_will_dispatch_add_test_hero_jobs()
    {
        Queue::fake();

        $heroRaces = HeroRace::starting()->get();
        $testID = rand(9999, 999999);
        /** @var CreateTestSquadAction $domainAction */
        $domainAction = app(CreateTestSquadAction::class);
        $squad = $domainAction->execute($testID);

        Queue::assertPushed(AddTestHeroToTestSquadJob::class, $heroRaces->count());

        $heroRaces->each(function (HeroRace $heroRace) use ($squad) {
            Queue::assertPushed(AddTestHeroToTestSquadJob::class, function (AddTestHeroToTestSquadJob $job) use ($squad, $heroRace) {
                return ($squad->id === $job->squad->id) && ($heroRace->id === $job->heroRace->id);
            });
        });
    }

}
