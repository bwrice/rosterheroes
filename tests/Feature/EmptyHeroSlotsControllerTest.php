<?php

namespace Tests\Feature;

use App\Domain\Actions\EmptyHeroSlotAction;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Slot;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Domain\Support\SlotTransaction;
use App\Nova\Hero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmptyHeroSlotsControllerTest extends TestCase
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
        $this->squad = factory(Squad::class)->states('with-slots')->create();

        $this->hero = factory(\App\Domain\Models\Hero::class)->states('with-slots', 'with-measurables')->create();

        factory(HeroPost::class)->create([
            'hero_id' => $this->hero->id,
            'squad_id' => $this->squad->id
        ]);
    }

    /**
     * @test
     */
    public function it_will_empty_slots_and_return_transaction()
    {
        $this->withoutExceptionHandling();

        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            return $builder->where('name', '=', ItemBase::BOW);
        })->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();

        $heroSlotsToFill = $this->hero->slots->filter(function (Slot $slot) {
            return in_array($slot->slotType->name, [
                SlotType::PRIMARY_ARM,
                SlotType::OFF_ARM
            ]);
        });

        $this->item->slots()->saveMany($heroSlotsToFill);

        $this->assertEquals(2, $heroSlotsToFill->count());

        $slot = $heroSlotsToFill->take(1)->first();

        Passport::actingAs($this->squad->user);

        $response = $this->json('POST','api/v1/heroes/' . $this->hero->slug . '/empty-slot', [
            'slot' => $slot->uuid
        ]);

        $response->assertStatus(200);

        $responseArray = json_decode($response->content(), true);
        $this->assertEquals(2, count($responseArray['data']));

        $response->assertJson([
            'data' => [
                'hero' => [
                    'uuid' => $this->hero->uuid
                ],
                'mobileStorage' => [
                    'slots' => []
                ]
            ]
        ]);
    }
}
