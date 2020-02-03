<?php

namespace Tests\Feature;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Measurable;
use App\Domain\Models\User;
use App\Facades\CurrentWeek;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RaiseMeasurableControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->state('with-measurables')->create();
        CurrentWeek::partialMock()->shouldReceive('adventuringOpen')->andReturn(true);
    }

    /**
     * @test
     */
    public function it_will_return_the_cost_to_raise()
    {
        /** @var Measurable $measurable */
        $measurable = $this->hero->measurables()->inRandomOrder()->first();
        Passport::actingAs($this->hero->squad->user);

        $queryVars = "?type=" . $measurable->measurableType->name;
        $response = $this->json('GET', 'api/v1/heroes/' . $this->hero->slug . '/raise-measurable' . $queryVars);
        $response->assertStatus(200);
        $costOne = (int) $response->getContent();
        $this->assertGreaterThan(0, $costOne);

        $queryVars = "?type=" . $measurable->measurableType->name . "&amount=5";
        $response = $this->json('GET', 'api/v1/heroes/' . $this->hero->slug . '/raise-measurable' . $queryVars);
        $response->assertStatus(200);
        $costTwo = (int) $response->getContent();
        $this->assertGreaterThan($costOne, $costTwo);
    }

    /**
     * @test
     */
    public function it_will_raise_a_hero_measurable()
    {
        $this->withoutExceptionHandling();

        /** @var Measurable $measurable */
        $measurable = $this->hero->measurables()->inRandomOrder()->first();
        $startingAmount = $measurable->amount_raised;
        $squad = $this->hero->squad;
        $squad->experience = 999999;
        $squad->save();
        $amount = 5;

        Passport::actingAs($this->hero->squad->user);

        $response = $this->json('POST', 'api/v1/heroes/' . $this->hero->slug . '/raise-measurable', [
            'type' => $measurable->measurableType->name,
            'amount' => $amount
        ]);

        $expectedAmountRaised = $startingAmount + $amount;

        $response->assertJson([
            'data' => [
                'uuid' => $this->hero->uuid
            ]
        ]);

        $measurable = $measurable->fresh();
        $this->assertEquals($expectedAmountRaised, $measurable->amount_raised);
    }

    /**
     * @test
     */
    public function a_user_cannot_raise_a_measurable_on_a_hero_it_doesnt_own()
    {
        /** @var Measurable $measurable */
        $measurable = $this->hero->measurables()->inRandomOrder()->first();
        $startingAmount = $measurable->amount_raised;
        $squad = $this->hero->squad;
        $squad->experience = 999999;
        $squad->save();
        $amount = 5;

        // Create random user
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $response = $this->json('POST', 'api/v1/heroes/' . $this->hero->slug . '/raise-measurable', [
            'type' => $measurable->measurableType->name,
            'amount' => $amount
        ]);

        $response->assertStatus(403);

        $measurable = $measurable->fresh();
        $this->assertEquals($startingAmount, $measurable->amount_raised);
    }
}
