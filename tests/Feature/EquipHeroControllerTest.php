<?php

namespace Tests\Feature;

use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Facades\CurrentWeek;
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
        $this->hero->items()->save($this->shield);
        $this->singleHandWeapon = factory(Item::class)->state('single-handed')->create();
        $this->hero->items()->save($this->singleHandWeapon);
        $this->twoHandedWeapon = factory(Item::class)->state('two-handed')->create();
        $this->squad->items()->save($this->twoHandedWeapon);
        CurrentWeek::partialMock()->shouldReceive('adventuringOpen')->andReturn(true);
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

        $response->assertStatus(200);

        $itemsMoved = collect($response->json('data'));

        $this->assertEquals(3, $itemsMoved->count());

        foreach([
            [
                'uuid' => (string) $this->singleHandWeapon->uuid,
                'to' => $this->squad->getTransactionIdentification(),
                'from' => $this->hero->getTransactionIdentification()
            ],
            [
                'uuid' => (string) $this->shield->uuid,
                'to' => $this->squad->getTransactionIdentification(),
                'from' => $this->hero->getTransactionIdentification()
            ],
            [
                'uuid' => (string) $this->twoHandedWeapon->uuid,
                'to' => $this->hero->getTransactionIdentification(),
                'from' => $this->squad->getTransactionIdentification()
            ],
                ] as $itemTransaction) {
            $matchingTransaction = $itemsMoved->first(function ($responseTransaction) use ($itemTransaction) {
                return $responseTransaction['uuid'] === $itemTransaction['uuid']
                    && $responseTransaction['transaction']['to'] === $itemTransaction['to']
                    && $responseTransaction['transaction']['from'] === $itemTransaction['from'];
            });

            $this->assertNotNull($matchingTransaction);
        }
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
