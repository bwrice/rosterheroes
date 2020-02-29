<?php

use App\Domain\Models\Attack;
use App\Domain\Models\Enchantment;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedStarterItemBlueprints extends Migration
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
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Short Sword' )->first(),
                    'material' => $materials->where( 'name', '=', 'Copper' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Strength'
                ],
                'attacks' => Attack::query()
                    ->whereIn('name', Attack::STARTER_SWORD_ATTACKS)
                    ->get()
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_SHIELD,
                    'description' => 'New warrior shield',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Buckler' )->first(),
                    'material' => $materials->where( 'name', '=', 'Copper' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Valor'
                ],
                'attacks' => [
                    // No attacks for shield
                ]
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_BOW,
                    'description' => 'New ranger bow',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Straight Bow' )->first(),
                    'material' => $materials->where( 'name', '=', 'Yew' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Agility',
                    'Level 1 Focus'
                ],
                'attacks' => Attack::query()
                    ->whereIn('name', Attack::STARTER_BOW_ATTACKS)
                    ->get()
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_STAFF,
                    'description' => 'New sorcerer staff',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Lesser Staff' )->first(),
                    'material' => $materials->where( 'name', '=', 'Yew' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Aptitude',
                    'Level 1 Intelligence'
                ],
                'attacks' => Attack::query()
                    ->whereIn('name', Attack::STARTER_STAFF_ATTACKS)
                    ->get()
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_ROBES,
                    'description' => 'New sorcerer robes',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Frock' )->first(),
                    'material' => $materials->where( 'name', '=', 'Cotton' )->first(),
                ],
                'enchantments' => [
                    "Beginner's Blessing"
                ],
                'attacks' => []
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_LIGHT_ARMOR,
                    'description' => 'New ranger light armor',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Light Cuirass' )->first(),
                    'material' => $materials->where( 'name', '=', 'Leather' )->first(),
                ],
                'enchantments' => [
                    "Beginner's Blessing"
                ],
                'attacks' => []
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_HEAVY_ARMOR,
                    'description' => 'New warrior heavy armor',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Breastplate' )->first(),
                    'material' => $materials->where( 'name', '=', 'Leather' )->first(),
                ],
                'enchantments' => [
                    "Beginner's Blessing"
                ],
                'attacks' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Completely random enchanted item',
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                ],
                'enchantments' => [],
                'attacks' => []
            ]
        ];

        foreach($blueprints as $blueprint) {

            /** @var ItemBlueprint $blueprintCreated */
            $blueprintCreated = ItemBlueprint::create([
                'item_name' => $blueprint['create_array']['name'],
                'description' => $blueprint['create_array']['description']
            ]);

            if (isset($blueprint['create_array']['item_type'])) {
                $blueprintCreated->itemTypes()->attach($blueprint['create_array']['item_type']);
            }
            if (isset($blueprint['create_array']['material'])) {
                $blueprintCreated->materialTypes()->attach($blueprint['create_array']['material']);
            }
            if (isset($blueprint['create_array']['item_class'])) {
                $blueprintCreated->itemClasses()->attach($blueprint['create_array']['item_class']);
            }
            $enchantmentsToAttach = $enchantments->whereIn('name', $blueprint['enchantments']);

            $blueprintCreated->enchantments()->saveMany($enchantmentsToAttach);
            $blueprintCreated->attacks()->saveMany($blueprint['attacks']);
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
