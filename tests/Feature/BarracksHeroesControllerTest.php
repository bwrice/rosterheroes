<?php

namespace Tests\Feature;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BarracksHeroesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_a_squads_heroes()
    {
        $this->withoutExceptionHandling();

        /** @var Hero $heroOne */
        $heroOne = factory(Hero::class)->create();

        factory(HeroPost::class)->create([
            'hero_id' => $heroOne->id
        ]);

        $squad = $heroOne->heroPost->squad;


        /** @var Hero $heroTwo */
        $heroTwo = factory(Hero::class)->create();


        factory(HeroPost::class)->create([
            'hero_id' => $heroTwo->id,
            'squad_id' => $squad->id
        ]);

        Passport::actingAs($squad->user);

        $response = $this->get('/api/v1/squads/' . $squad->slug . '/barracks/heroes');
        $this->assertEquals(200, $response->status());
    }
}
