<?php

use App\Domain\Models\Attack;
use App\Domain\Models\Enchantment;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedItemBlueprints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $itemTypes = ItemType::all();
        $itemClasses = ItemClass::all();
        $itemBases = ItemBase::all();
        $materials = Material::all();
        $enchantments = Enchantment::all();

        $blueprints = [

            /*
             * Starter Items
             */
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_SWORD,
                    'description' => 'New warrior sword',
                    'reference_id' => 'A',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Short Sword' )->first(),
                    'material' => $materials->where( 'name', '=', 'Copper' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Strength'
                ]
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_SHIELD,
                    'description' => 'New warrior shield',
                    'reference_id' => 'B',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Buckler' )->first(),
                    'material' => $materials->where( 'name', '=', 'Copper' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Valor'
                ]
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_BOW,
                    'description' => 'New ranger bow',
                    'reference_id' => 'C',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Straight Bow' )->first(),
                    'material' => $materials->where( 'name', '=', 'Yew' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Agility',
                    'Level 1 Focus'
                ]
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_STAFF,
                    'description' => 'New sorcerer staff',
                    'reference_id' => 'D',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Lesser Staff' )->first(),
                    'material' => $materials->where( 'name', '=', 'Yew' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Aptitude',
                    'Level 1 Intelligence'
                ]
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_ROBES,
                    'description' => 'New sorcerer robes',
                    'reference_id' => 'E',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Frock' )->first(),
                    'material' => $materials->where( 'name', '=', 'Cotton' )->first(),
                ],
                'enchantments' => [
                    "Beginner's Blessing"
                ]
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_LIGHT_ARMOR,
                    'description' => 'New ranger light armor',
                    'reference_id' => 'F',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Light Cuirass' )->first(),
                    'material' => $materials->where( 'name', '=', 'Leather' )->first(),
                ],
                'enchantments' => [
                    "Beginner's Blessing"
                ]
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_HEAVY_ARMOR,
                    'description' => 'New warrior heavy armor',
                    'reference_id' => 'G',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Breastplate' )->first(),
                    'material' => $materials->where( 'name', '=', 'Leather' )->first(),
                ],
                'enchantments' => [
                    "Beginner's Blessing"
                ]
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Completely random enchanted item',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_REFERENCE,
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted dagger',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_DAGGER,
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_base' => $itemBases->where('name', '=', ItemBase::DAGGER)->first()
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted sword',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SWORD,
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_base' => $itemBases->where('name', '=', ItemBase::SWORD)->first()
                ],
                'enchantments' => []
            ]
        ];

        foreach($blueprints as $blueprint) {

            /** @var ItemBlueprint $blueprintCreated */
            $blueprintCreated = ItemBlueprint::create([
                'item_name' => $blueprint['create_array']['name'],
                'description' => $blueprint['create_array']['description'],
                'reference_id' => $blueprint['create_array']['reference_id']
            ]);

            if (isset($blueprint['create_array']['item_type'])) {
                $blueprintCreated->itemTypes()->attach($blueprint['create_array']['item_type']);
            }
            if (isset($blueprint['create_array']['item_base'])) {
                $blueprintCreated->itemBases()->attach($blueprint['create_array']['item_base']);
            }
            if (isset($blueprint['create_array']['material'])) {
                $blueprintCreated->materials()->attach($blueprint['create_array']['material']);
            }
            if (isset($blueprint['create_array']['item_class'])) {
                $blueprintCreated->itemClasses()->attach($blueprint['create_array']['item_class']);
            }
            $enchantmentsToAttach = $enchantments->whereIn('name', $blueprint['enchantments']);

            $blueprintCreated->enchantments()->saveMany($enchantmentsToAttach);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
