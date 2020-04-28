<?php

namespace Tests\Feature;

use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Facades\CurrentWeek;
use App\Nova\Hero;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Throwable;

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

        Sanctum::actingAs($this->squad->user);

        $response = $this->json('POST','api/v1/heroes/' . $this->hero->slug . '/unequip', [
            'item' => $this->item->uuid
        ]);

        $response->assertStatus(200);

        $this->assertEquals(1, count($response->json('data')));

        $response->assertJson([
            'data' => [
                [
                    'uuid' => $this->item->uuid,
                    'transaction' => [
                        'to' => $this->squad->getTransactionIdentification(),
                        'from' => $this->hero->getTransactionIdentification()
                    ]
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function a_user_not_owning_the_hero_will_be_unauthorized_to_un_equip_item()
    {
        $diffUser = factory(User::class)->create();
        Sanctum::actingAs($diffUser);

        $response = $this->json('POST','api/v1/heroes/' . $this->hero->slug . '/unequip', [
            'item' => $this->item->uuid
        ]);

        $response->assertStatus(403);
    }
}
