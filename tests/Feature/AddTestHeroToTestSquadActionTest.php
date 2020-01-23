<?php

namespace Tests\Feature;

use App\Domain\Actions\Testing\AddTestHeroToTestSquadAction;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddTestHeroToTestSquadActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var int */
    protected $testID;

    /** @var Squad */
    protected $squad;

    /** @var AddTestHeroToTestSquadAction */
    protected $domainAction;

    /** @var HeroRace */
    protected $heroRace;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->testID = random_int(10000, 99999);
        $this->squad = factory(Squad::class)->state('starting-posts')->create([
            'name' => 'TestSquad' . $this->testID
        ]);
        $this->heroRace = HeroRace::starting()->inRandomOrder()->first();
        $this->domainAction = app(AddTestHeroToTestSquadAction::class);
    }

    /**
    * @test
    */
    public function it_will_create_a_hero_of_the_given_race_for_the_test_squad()
    {
        $hero = $this->domainAction->execute($this->squad, $this->heroRace, $this->testID);
        $this->assertEquals($this->squad->id, $hero->squad_id);
        $this->assertEquals($this->heroRace->id, $hero->hero_race_id);
    }
}
