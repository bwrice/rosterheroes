<?php

namespace Tests\Feature;

use App\Campaign;
use App\Hero;
use App\HeroClass;
use App\Heroes\HeroPosts\HeroPost;
use App\HeroRace;
use App\Measurable;
use App\MeasurableType;
use App\Slots\Slot;
use App\SlotType;
use App\Squad;
use App\User;
use App\Weeks\Week;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_new_squad_can_be_created()
    {
        /** @var User $user */
        $user = Passport::actingAs(factory(User::class)->create());

        $name = 'TestSquad' . rand(1,999999);

        $response = $this->json('POST','api/squads', [
           'name' => $name
        ]);

        $response->assertStatus(201);

        /** @var Squad $squad */
        $squad = Squad::where('name', $name)->first();

        $this->assertEquals($user->squads->first()->id, $squad->id);
        $this->assertEquals(Squad::STARTING_SALARY, $squad->salary, "Squad has starting salary");
        $this->assertEquals(Squad::STARTING_GOLD, $squad->gold, "Squad has starting gold");
        $this->assertEquals(Squad::STARTING_FAVOR, $squad->favor, "Squad has starting favor");

        $this->assertEquals($squad->mobileStorageRank->getBehavior()->getSlotsCount(), $squad->slots()->count(), "Squad has it's slots");

        $this->assertEquals( count(Squad::STARTING_HERO_POSTS), $squad->heroPosts->count(), 'Squad has correct number of hero posts');

       foreach( Squad::STARTING_HERO_POSTS as $heroRaceName => $count ) {
           $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();
           $this->assertEquals($count, $squad->heroPosts->where('hero_race_id', '=', $heroRace->id)->count(), "Correct amount of hero posts by hero race");
       }
    }

    /**
     * @test
     */
    public function a_squad_can_create_a_campaign()
    {
        $this->withoutExceptionHandling();

        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var User $user */
        $user = Passport::actingAs($squad->user);

        $response = $this->json('POST','api/squad/' . $squad->uuid . '/campaigns');
        $response->assertStatus(201);

        /** @var Campaign $campaign */
        $campaign = $squad->campaigns()->first();
        $this->assertEquals($squad->id, $campaign->squad_id);
        $this->assertEquals($week->id, $campaign->week_id);
        $this->assertEquals($squad->province->continent_id, $campaign->continent_id);
    }
}
