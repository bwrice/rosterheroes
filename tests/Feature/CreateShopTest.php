<?php

namespace Tests\Feature;

use App\Domain\Actions\CreateShop;
use App\Domain\Collections\ItemBlueprintCollection;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Province;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateShopTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * @return CreateShop
     */
    protected function getDomainAction()
    {
        return app(CreateShop::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_shop_with_the_correct_atributes()
    {
        $name = $this->faker->streetName;
        $province = Province::query()->inRandomOrder()->first();

        $tier = rand(1,3);
        $shop = $this->getDomainAction()->execute($name, $tier, $province, new ItemBlueprintCollection());

        $this->assertEquals($name, $shop->name);
        $this->assertEquals($province->id, $shop->province_id);
        $this->assertEquals($tier, $shop->tier);
    }

    /**
     * @test
     */
    public function it_will_create_shop_and_attach_item_blueprints()
    {
        $name = $this->faker->streetName;
        $province = Province::query()->inRandomOrder()->first();

        $amount = rand(3, 8);
        /** @var ItemBlueprintCollection $itemBlueprints */
        $itemBlueprints = ItemBlueprint::query()->inRandomOrder()->take($amount)->get();

        $shop = $this->getDomainAction()->execute($name, 1, $province, $itemBlueprints);

        $this->assertEquals($amount, $shop->itemBlueprints->count());
    }
}
