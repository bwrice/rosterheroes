<?php

namespace Tests\Feature;

use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Facades\CurrentWeek;
use App\Nova\Hero;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnEquipHeroControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Item */
    protected $item;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    public function setUp(): void
    {
        parent::setUp();

        $this->item = factory(Item::class)->create();
        $this->hero = factory(\App\Domain\Models\Hero::class)->states( 'with-measurables')->create();
        $this->squad = $this->hero->squad;
        $this->item->attachToHasItems($this->hero);
        CurrentWeek::partialMock()->shouldReceive('adventuringOpen')->andReturn(true);
    }

    /**
     * @test
     */
    public function it_will_unequip_item_and_return_correct_response()
    {

        Passport::actingAs($this->squad->user);

        $response = $this->json('POST','api/v1/heroes/' . $this->hero->slug . '/unequip', [
            'item' => $this->item->uuid
        ]);

        $response->assertStatus(200);

        $responseArray = json_decode($response->content(), true);
        $this->assertEquals(2, count($responseArray['data']));

        $response->assertJson([
            'data' => [
                [
                    'hasItems' => [
                        'uuid' => $this->hero->uuid
                    ],
                    'type' => 'hero'
                ],
                [
                    'hasItems' => [
                        'mobileStorageRank' => []
                    ],
                    'type' => 'squad'
                ]
            ]
        ]);
    }
}
