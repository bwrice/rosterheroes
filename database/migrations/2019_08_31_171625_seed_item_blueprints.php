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
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_types' => $itemTypes->where( 'name', '=', 'Short Sword' ),
                    'materials' => $materials->where( 'name', '=', 'Copper' ),
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
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_types' => $itemTypes->where( 'name', '=', 'Buckler' ),
                    'materials' => $materials->where( 'name', '=', 'Copper' ),
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
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_types' => $itemTypes->where( 'name', '=', 'Straight Bow' ),
                    'materials' => $materials->where( 'name', '=', 'Yew' ),
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
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_types' => $itemTypes->where( 'name', '=', 'Lesser Staff' ),
                    'materials' => $materials->where( 'name', '=', 'Yew' ),
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
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_types' => $itemTypes->where( 'name', '=', 'Frock' ),
                    'materials' => $materials->where( 'name', '=', 'Cotton' ),
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
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_types' => $itemTypes->where( 'name', '=', 'Light Cuirass' ),
                    'materials' => $materials->where( 'name', '=', 'Leather' ),
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
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_types' => $itemTypes->where( 'name', '=', 'Breastplate' ),
                    'materials' => $materials->where( 'name', '=', 'Leather' ),
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
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted dagger',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_DAGGER,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::DAGGER)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted sword',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SWORD,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::SWORD)
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

            if (isset($blueprint['create_array']['item_types'])) {
                $blueprintCreated->itemTypes()->saveMany($blueprint['create_array']['item_types']);
            }
            if (isset($blueprint['create_array']['item_bases'])) {
                $blueprintCreated->itemBases()->saveMany($blueprint['create_array']['item_bases']);
            }
            if (isset($blueprint['create_array']['materials'])) {
                $blueprintCreated->materials()->saveMany($blueprint['create_array']['materials']);
            }
            if (isset($blueprint['create_array']['item_classes'])) {
                $blueprintCreated->itemClasses()->saveMany($blueprint['create_array']['item_classes']);
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
