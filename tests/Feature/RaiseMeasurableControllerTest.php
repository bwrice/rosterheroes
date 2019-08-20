<?php

namespace Tests\Feature;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Measurable;
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
}
