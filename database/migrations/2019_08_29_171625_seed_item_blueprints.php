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
        $materials = Material::all();
        $enchantments = Enchantment::all();

        $blueprints = [

            /*
             * Starter Items
             */
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_SWORD,
                    'item_class' => $itemClasses->where('name', '=', ItemClass::ENCHANTED)->first(),
                    'item_type' => $itemTypes->where( 'name', '=', 'Short Sword' )->first(),
                    'material' => $materials->where( 'name', '=', 'Copper' )->first(),
                ],
                'enchantments' => [
                    'Level 1 Strength'
                ],
                'attacks' => Attack::query()
                    ->whereIn('name', Attack::START_SWORD_ATTACKS)
                    ->get()
            ],
            [
                'create_array' => [
                    'name' => ItemBlueprint::STARTER_SHIELD,
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
            ]
        ];

        foreach($blueprints as $blueprint) {

            /** @var ItemBlueprint $blueprintCreated */
            $blueprintCreated = ItemBlueprint::create([
                'item_name' => $blueprint['create_array']['name']
            ]);

            $blueprintCreated->itemTypes()->attach($blueprint['create_array']['item_type']);
            $blueprintCreated->materialTypes()->attach($blueprint['create_array']['material']);
            $blueprintCreated->itemClasses()->attach($blueprint['create_array']['item_class']);

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
