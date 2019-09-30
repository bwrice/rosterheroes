<?php

namespace Tests\Feature;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Measurable;
use App\Domain\Models\User;
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

    /** @var HeroPost */
    protected $heroPost;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->state('with-measurables')->create();
        $this->heroPost = factory(HeroPost::class)->create();
        $this->heroPost->hero_id = $this->hero->id;
        $this->heroPost->save();
    }

    /**
     * @test
     */
    public function it_will_return_the_cost_to_raise()
    {
        /** @var Measurable $measurable */
        $measurable = $this->hero->measurables()->inRandomOrder()->first();
        Passport::actingAs($this->heroPost->squad->user);

        $response = $this->json('GET', 'api/v1/measurables/' . $measurable->uuid . '/raise');
        $response->assertStatus(200);
        $costOne = (int) $response->getContent();
        $this->assertGreaterThan(0, $costOne);

        $response = $this->json('GET', 'api/v1/measurables/' . $measurable->uuid . '/raise?amount=5');
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
        $squad = $this->hero->getSquad();
        $squad->experience = 999999;
        $squad->save();
        $amount = 5;

        Passport::actingAs($this->heroPost->squad->user);

        $response = $this->json('POST', 'api/v1/measurables/' . $measurable->uuid . '/raise', [
            'amount' => $amount
        ]);

        $expectedAmountRaised = $startingAmount + $amount;

        $response->assertJson([
            'data' => [
                'uuid' => $measurable->uuid,
                'amountRaised' => $expectedAmountRaised
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
        $squad = $this->hero->getSquad();
        $squad->experience = 999999;
        $squad->save();
        $amount = 5;

        // Create random user
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $response = $this->json('POST', 'api/v1/measurables/' . $measurable->uuid . '/raise', [
            'amount' => $amount
        ]);

        $response->assertStatus(403);

        $measurable = $measurable->fresh();
        $this->assertEquals($startingAmount, $measurable->amount_raised);
    }
}
