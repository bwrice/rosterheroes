<?php

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
        $itemTypes = \App\Domain\Models\ItemType::all();
        $itemClasses = \App\Domain\Models\ItemClass::all();
        $materialTypes = \App\Domain\Models\MaterialType::all();
        $enchantments = \App\Domain\Models\Enchantment::all();

        $blueprints = [

            /*
             * Starter Items
             */
            [
                'create_array' => [
                    'name' => \App\Domain\Models\ItemBlueprint::STARTER_SWORD,
                    'item_class_id' => $itemClasses->where('name', '=', \App\Domain\Models\ItemClass::ENCHANTED )->first()->id,
                    'item_type_id' => $itemTypes->where( 'name', '=', 'Short Sword' )->first()->id,
                    'material_type_id' => $materialTypes->where( 'name', '=', 'Copper' )->first()->id,
                ],
                'enchantments' => [
                    'Level 1 Strength'
                ]
            ],
            [
                'create_array' => [
                    'name' => \App\Domain\Models\ItemBlueprint::STARTER_SHIELD,
                    'item_class_id' => $itemClasses->where('name', '=', \App\Domain\Models\ItemClass::ENCHANTED )->first()->id,
                    'item_type_id' => $itemTypes->where( 'name', '=', 'Buckler' )->first()->id,
                    'material_type_id' => $materialTypes->where( 'name', '=', 'Copper' )->first()->id,
                ],
                'enchantments' => [
                    'Level 1 Valor'
                ]
            ],
            [
                'create_array' => [
                    'name' => \App\Domain\Models\ItemBlueprint::STARTER_BOW,
                    'item_class_id' => $itemClasses->where('name', '=', \App\Domain\Models\ItemClass::ENCHANTED )->first()->id,
                    'item_type_id' => $itemTypes->where( 'name', '=', 'Straight Bow' )->first()->id,
                    'material_type_id' => $materialTypes->where( 'name', '=', 'Yew' )->first()->id,
                ],
                'enchantments' => [
                    'Level 1 Agility',
                    'Level 1 Focus'
                ]
            ],
            [
                'create_array' => [
                    'name' => \App\Domain\Models\ItemBlueprint::STARTER_STAFF,
                    'item_class_id' => $itemClasses->where('name', '=', \App\Domain\Models\ItemClass::ENCHANTED )->first()->id,
                    'item_type_id' => $itemTypes->where( 'name', '=', 'Lesser Staff' )->first()->id,
                    'material_type_id' => $materialTypes->where( 'name', '=', 'Yew' )->first()->id,
                ],
                'enchantments' => [
                    'Level 1 Aptitude',
                    'Level 1 Intelligence'
                ]
            ]
        ];

        foreach( $blueprints as $blueprint ) {

            /** @var \App\Domain\Models\ItemBlueprint $blueprintCreated */
            $blueprintCreated = \App\Domain\Models\ItemBlueprint::create([
                'item_name' => $blueprint['create_array']['name'],
                'item_class_id' => $blueprint['create_array']['item_class_id'],
                'item_type_id' => $blueprint['create_array']['item_type_id'],
                'material_type_id' => $blueprint['create_array']['material_type_id']
            ]);

            $enchantmentsToAttach = $enchantments->whereIn('name', $blueprint['enchantments']);

            $blueprintCreated->enchantments()->saveMany( $enchantmentsToAttach );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('enchantment_item_blueprint')->truncate();
        \App\Domain\Models\Item::query()->whereHas('itemBlueprint')->delete();
        \App\Domain\Models\ItemBlueprint::query()->delete();
    }
}
