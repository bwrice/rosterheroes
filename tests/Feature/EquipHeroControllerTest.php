<?php

namespace Tests\Feature;

use App\Domain\Actions\EquipHeroSlotFromWagonAction;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Slot;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Support\SlotTransaction;
use App\Nova\Hero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;

class EquipHeroControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Item */
    protected $shield;

    /** @var Item */
    protected $mace;

    /** @var Item */
    protected $twoHandSword;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var Slot */
    protected $primaryArm;

    /** @var Slot */
    protected $offArm;

    public function setUp(): void
    {
        parent::setUp();

        $this->squad = factory(Squad::class)->states('with-slots')->create();
        $this->hero = factory(\App\Domain\Models\Hero::class)->states('with-slots', 'with-measurables')->create();

        factory(HeroPost::class)->create([
            'hero_id' => $this->hero->id,
            'squad_id' => $this->squad->id
        ]);

        $shieldType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            return $builder->where('name', '=', ItemBase::SHIELD);
        })->first();
        $this->shield = factory(Item::class)->create([
            'item_type_id' => $shieldType->id
        ]);

        $maceType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            return $builder->where('name', '=', ItemBase::MACE);
        })->first();
        $this->mace = factory(Item::class)->create([
            'item_type_id' => $maceType->id
        ]);

        $twoHandSwordType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            return $builder->where('name', '=', ItemBase::TWO_HAND_SWORD);
        })->first();
        $this->twoHandSword = factory(Item::class)->create([
            'item_type_id' => $twoHandSwordType->id
        ]);

        $this->primaryArm = $this->hero->slots->first(function (Slot $slot) {
            return $slot->slotType->name === SlotType::PRIMARY_ARM;
        });

        $this->offArm = $this->hero->slots->first(function (Slot $slot) {
            return $slot->slotType->name === SlotType::OFF_ARM;
        });

        $squadSlots = $this->squad->slots()->take($this->twoHandSword->getSlotsCount())->get();
        $this->twoHandSword->slots()->saveMany($squadSlots);

        $this->offArm->item_id = $this->shield->id;
        $this->offArm->save();

        $this->primaryArm->item_id = $this->mace->id;
        $this->primaryArm->save();
    }

    /**
     * @test
     */
    public function it_will_equip_a_hero_owned_by_the_current_user()
    {
        $this->withoutExceptionHandling();

        Passport::actingAs($this->squad->user);

        $response = $this->json('POST','api/v1/heroes/' . $this->hero->slug . '/equip', [
            'slot' => $this->primaryArm->uuid,
            'item' => $this->twoHandSword->uuid
        ]);

        $response->assertJson([
                'data' => [
                    'hero' => [
                        'uuid' => $this->hero->uuid
                    ],
                    'mobileStorage' => [
                        'slots' => [

                        ]
                    ]
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
            'slot' => $this->primaryArm->uuid,
            'item' => $this->twoHandSword->uuid
        ]);

        $response->assertStatus(403);
    }
}
