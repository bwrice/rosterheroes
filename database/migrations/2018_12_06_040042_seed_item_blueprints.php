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
                    'item_class' => $itemClasses->where('name', '=', \App\Domain\Models\ItemClass::ENCHANTED )->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Short Sword' )->first(),
                    'material_type' => $materialTypes->where( 'name', '=', 'Copper' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Strength'
                ]
            ],
            [
                'create_array' => [
                    'name' => \App\Domain\Models\ItemBlueprint::STARTER_SHIELD,
                    'item_class' => $itemClasses->where('name', '=', \App\Domain\Models\ItemClass::ENCHANTED )->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Buckler' )->first(),
                    'material_type' => $materialTypes->where( 'name', '=', 'Copper' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Valor'
                ]
            ],
            [
                'create_array' => [
                    'name' => \App\Domain\Models\ItemBlueprint::STARTER_BOW,
                    'item_class' => $itemClasses->where('name', '=', \App\Domain\Models\ItemClass::ENCHANTED )->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Straight Bow' )->first(),
                    'material_type' => $materialTypes->where( 'name', '=', 'Yew' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Agility',
                    'Level 1 Focus'
                ]
            ],
            [
                'create_array' => [
                    'name' => \App\Domain\Models\ItemBlueprint::STARTER_STAFF,
                    'item_class' => $itemClasses->where('name', '=', \App\Domain\Models\ItemClass::ENCHANTED )->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Lesser Staff' )->first(),
                    'material_type' => $materialTypes->where( 'name', '=', 'Yew' )->first(),
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
                'item_name' => $blueprint['create_array']['name']
            ]);

            $blueprintCreated->itemTypes()->attach($blueprint['create_array']['item_type']);
            $blueprintCreated->materialTypes()->attach($blueprint['create_array']['material_type']);
            $blueprintCreated->itemClasses()->attach($blueprint['create_array']['item_class']);

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
