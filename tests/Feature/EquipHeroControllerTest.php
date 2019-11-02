<?php

namespace Tests\Feature;

use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Nova\Hero;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;

class EquipHeroControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Item */
    protected $shield;

    /** @var Item */
    protected $singleHandWeapon;

    /** @var Item */
    protected $twoHandedWeapon;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    public function setUp(): void
    {
        parent::setUp();

        $this->hero = factory(\App\Domain\Models\Hero::class)->states('with-measurables')->create();
        $this->squad = $this->hero->squad;
        $this->shield = factory(Item::class)->state('shield')->create();
        $this->shield->attachToHasItems($this->hero);
        $this->singleHandWeapon = factory(Item::class)->state('single-handed')->create();
        $this->singleHandWeapon->attachToHasItems($this->hero);
        $this->twoHandedWeapon = factory(Item::class)->state('two-handed')->create();
        $this->twoHandedWeapon->attachToHasItems($this->squad);

    }

    /**
     * @test
     */
    public function it_will_equip_a_hero_owned_by_the_current_user()
    {
        $this->withoutExceptionHandling();

        Passport::actingAs($this->squad->user);

        $response = $this->json('POST','api/v1/heroes/' . $this->hero->slug . '/equip', [
            'item' => $this->twoHandedWeapon->uuid
        ]);

        $responseArray = json_decode($response->content(), true);
        $this->assertEquals(2, count($responseArray['data']));

        $response->assertJson([
                'data' => [
                    [
                        'hasItems' => [
                            'mobileStorageRank' => []
                        ],
                        'type' => 'squad'
                    ],
                    [
                        'hasItems' => [
                            'uuid' => $this->hero->uuid
                        ],
                        'type' => 'hero'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_not_equip_an_item_to_a_hero_that_doesnt_belong_to_them()
    {
        $differentUser = factory(User::class)->create();
        Passport::actingAs($differentUser);

        $response = $this->json('POST','api/v1/heroes/' . $this->hero->slug . '/equip', [
            'item' => $this->twoHandedWeapon->uuid
        ]);

        $response->assertStatus(403);
    }
}
