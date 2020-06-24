<?php

namespace Tests\Feature;

use App\Domain\Actions\CreateShop;
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
    public function it_will_create_a_tier_1_shop_with_the_name_and_province()
    {
        $name = $this->faker->streetName;
        $province = Province::query()->inRandomOrder()->first();

        $shop = $this->getDomainAction()->execute($name, $province);

        $this->assertEquals($name, $shop->name);
        $this->assertEquals($province->id, $shop->province_id);
        $this->assertEquals(1, $shop->tier);
    }

    /**
     * @test
     */
    public function it_will_create_shop_with_a_handful_of_item_blueprints()
    {
        $name = $this->faker->streetName;
        $province = Province::query()->inRandomOrder()->first();

        $shop = $this->getDomainAction()->execute($name, $province);


        $this->assertGreaterThan(5, $shop->itemBlueprints);
    }
}
