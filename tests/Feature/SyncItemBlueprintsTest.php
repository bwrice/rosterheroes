<?php

namespace Tests\Feature;

use App\Admin\Content\Actions\SyncItemBlueprints;
use App\Admin\Content\Sources\ItemBlueprintSource;
use App\Domain\Models\Enchantment;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\Material;
use App\Facades\Content;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\ItemTypeFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class SyncItemBlueprintsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return SyncItemBlueprints
     */
    protected function getDomainAction()
    {
        return app(SyncItemBlueprints::class);
    }

    /**
     * @test
     */
    public function it_will_add_a_missing_item_blueprint()
    {

        $attacks = new Collection();

        $attackFactory = AttackFactory::new();

        $range = rand(1, 5);
        for ($i = 1; $i <= $range; $i++) {
            $attacks->push($attackFactory->create());
        }

        $itemTypes = new Collection();

        $itemTypeFactory = ItemTypeFactory::new();

        for ($i = 1; $i <= $range; $i++) {
            $itemTypes->push($itemTypeFactory->create());
        }

        $uuid = (string) Str::uuid();

        $itemBlueprintSource = new ItemBlueprintSource(
            $uuid,
            'Test ItemBlueprint ' . Str::random(),
            'Test Description ' . Str::random(),
            rand(1,6),
            ItemBase::query()->inRandomOrder()->take(rand(1,3))->pluck('id')->toArray(),
            ItemClass::query()->inRandomOrder()->take(rand(1,3))->pluck('id')->toArray(),
            $itemTypes->pluck('uuid')->toArray(),
            $attacks->pluck('uuid')->toArray(),
            // TODO use factories once materials and enchantments are seeded trough content-syncing
            Material::query()->inRandomOrder()->take(rand(1,5))->pluck('uuid')->toArray(),
            Enchantment::query()->inRandomOrder()->take(rand(1,6))->pluck('uuid')->toArray()
        );


        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect([$itemBlueprintSource]));

        $this->getDomainAction()->execute();

        /** @var ItemBlueprint $createdItemBlueprint */
        $createdItemBlueprint = ItemBlueprint::query()->where('uuid', '=', $uuid)->first();

        $this->assertNotNull($createdItemBlueprint);
        $this->assertEquals($itemBlueprintSource->getItemName(), $createdItemBlueprint->item_name);
        $this->assertEquals($itemBlueprintSource->getDescription(), $createdItemBlueprint->description);
        $this->assertEquals($itemBlueprintSource->getEnchantmentPower(), $createdItemBlueprint->enchantment_power);
        $this->assertArrayElementsEqual($itemBlueprintSource->getItemBases(), $createdItemBlueprint->itemBases()->pluck('id')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getItemClasses(), $createdItemBlueprint->itemClasses()->pluck('id')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getItemTypes(), $createdItemBlueprint->itemTypes()->pluck('uuid')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getAttacks(), $createdItemBlueprint->attacks()->pluck('uuid')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getMaterials(), $createdItemBlueprint->materials()->pluck('uuid')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getEnchantments(), $createdItemBlueprint->enchantments()->pluck('uuid')->toArray());
    }

    protected function assertArrayElementsEqual(array $arrayOne, array $arrayTwo)
    {
        sort($arrayOne);
        sort($arrayTwo);
        $this->assertEquals($arrayOne, $arrayTwo);
    }
}
