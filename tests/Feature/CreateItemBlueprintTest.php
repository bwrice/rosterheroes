<?php

namespace Tests\Feature;

use App\Domain\Actions\CreateItemBlueprint;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Exceptions\CreateItemBlueprintException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateItemBlueprintTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * @return CreateItemBlueprint
     */
    protected function getDomainAction()
    {
        return app(CreateItemBlueprint::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_it_cant_find_an_item_type()
    {
        $referenceID = (string) Str::uuid();

        $itemTypeNames = [
            'Knife',
            Str::random(10)
        ];

        try {
            $this->getDomainAction()->execute($referenceID, null, $itemTypeNames);
        } catch (CreateItemBlueprintException $exception) {
            $this->assertEquals(CreateItemBlueprintException::CODE_INVALID_ITEM_TYPES, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_it_cant_find_a_material()
    {
        $referenceID = (string) Str::uuid();

        $materialNames = [
            'Iron',
            Str::random(10)
        ];

        try {
            $this->getDomainAction()->execute($referenceID, null, [], $materialNames);
        } catch (CreateItemBlueprintException $exception) {
            $this->assertEquals(CreateItemBlueprintException::CODE_INVALID_MATERIALS, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_it_cant_find_a_item_class()
    {
        $referenceID = (string) Str::uuid();

        $itemClassNames = [
            ItemClass::GENERIC,
            Str::random(10)
        ];

        try {
            $this->getDomainAction()->execute($referenceID, null, [], [], $itemClassNames);
        } catch (CreateItemBlueprintException $exception) {
            $this->assertEquals(CreateItemBlueprintException::CODE_INVALID_ITEM_CLASSES, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_it_cant_find_an_item_base()
    {
        $referenceID = (string) Str::uuid();

        $itemBaseNames = [
            ItemClass::GENERIC,
            Str::random(10)
        ];

        try {
            $this->getDomainAction()->execute($referenceID, null, [], [], [], $itemBaseNames);
        } catch (CreateItemBlueprintException $exception) {
            $this->assertEquals(CreateItemBlueprintException::CODE_INVALID_ITEM_BASES, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_create_an_item_blueprint_with_the_correct_properties()
    {
        $referenceID = (string) Str::uuid();
        $description = $this->faker->words(5, true);
        $itemName = $this->faker->streetName;
        $enchantmentPower = rand(5, 100);

        $itemBlueprint = $this->getDomainAction()->execute(
            $referenceID,
            $description,
            [],
            [],
            [],
            [],
            $enchantmentPower,
            $itemName
        );

        $this->assertEquals($referenceID, $itemBlueprint->reference_id);
        $this->assertEquals($description, $itemBlueprint->description);
        $this->assertEquals($itemName, $itemBlueprint->item_name);
        $this->assertEquals($enchantmentPower, $itemBlueprint->enchantment_power);
    }

    /**
     * @test
     */
    public function it_will_attach_the_proper_relations_to_the_item_blueprint()
    {
        $referenceID = (string) Str::uuid();

        $itemTypeNames = ItemType::query()->inRandomOrder()->take(rand(2,5))->pluck('name')->toArray();
        $materialNames = Material::query()->inRandomOrder()->take(rand(2,5))->pluck('name')->toArray();
        $classNames = ItemClass::query()->inRandomOrder()->take(rand(2,5))->pluck('name')->toArray();

        $itemBlueprint = $this->getDomainAction()->execute(
            $referenceID,
            null,
            $itemTypeNames,
            $materialNames,
            $classNames,
            [],
            null,
            null
        );

        $this->assertEquals(count($itemTypeNames), $itemBlueprint->itemTypes()->count());
        $this->assertEquals(count($materialNames), $itemBlueprint->materials()->count());
        $this->assertEquals(count($classNames), $itemBlueprint->itemClasses()->count());
    }

    /**
     * @test
     */
    public function it_will_attach_item_bases_if_no_item_types()
    {
        $referenceID = (string) Str::uuid();

        $itemBaseNames = ItemBase::query()->inRandomOrder()->take(rand(2,5))->pluck('name')->toArray();

        $itemBlueprint = $this->getDomainAction()->execute(
            $referenceID,
            null,
            [],
            [],
            [],
            $itemBaseNames,
            null,
            null
        );

        $this->assertEquals(count($itemBaseNames), $itemBlueprint->itemBases()->count());
    }

    /**
     * @test
     */
    public function it_will_not_save_item_bases_if_there_are_item_types()
    {
        $referenceID = (string) Str::uuid();

        $itemTypeNames = ItemType::query()->inRandomOrder()->take(rand(2,5))->pluck('name')->toArray();
        $itemBaseNames = ItemBase::query()->inRandomOrder()->take(rand(2,5))->pluck('name')->toArray();


        $itemBlueprint = $this->getDomainAction()->execute(
            $referenceID,
            null,
            $itemTypeNames,
            [],
            [],
            $itemBaseNames,
            null,
            null
        );

        $this->assertEquals(count($itemTypeNames), $itemBlueprint->itemTypes()->count());
        $this->assertEquals(0, $itemBlueprint->itemBases()->count());
    }
}
