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

            /*
             * Random Items
             */
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
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted axe',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_AXE,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::AXE)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted mace',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MACE,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::MACE)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted bow',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BOW,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::BOW)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted crossbow',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_CROSSBOW,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::CROSSBOW)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted throwing-weapon',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_THROWING_WEAPON,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::THROWING_WEAPON)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted polearm',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_POLEARM,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::POLEARM)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted two-hand sword',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_TWO_HAND_SWORD,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::TWO_HAND_SWORD)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted two-hand axe',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_TWO_HAND_AXE,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::TWO_HAND_AXE)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted wand',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_WAND,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::WAND)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted orb',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ORB,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::ORB)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted staff',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_STAFF,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::STAFF)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted psionic one-hand',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_ONE_HAND,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::PSIONIC_ONE_HAND)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted psionic two-hand',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_TWO_HAND,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::PSIONIC_TWO_HAND)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted shield',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SHIELD,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::SHIELD)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted psionic shield',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_SHIELD,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::PSIONIC_SHIELD)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted helmet',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HELMET,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::HELMET)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted cap',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_CAP,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::CAP)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted heavy armor',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HEAVY_ARMOR,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::HEAVY_ARMOR)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted light armor',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LIGHT_ARMOR,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::LIGHT_ARMOR)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted leggings',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LEGGINGS,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::LEGGINGS)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted robes',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ROBES,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::ROBES)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted gloves',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_GLOVES,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::GLOVES)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted gauntlets',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_GAUNTLETS,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::GAUNTLETS)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted shoes',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SHOES,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::SHOES)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted boots',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BOOTS,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::BOOTS)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted belt',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BELT,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::BELT)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted sash',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SASH,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::SASH)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted necklace',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_NECKLACE,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::NECKLACE)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted bracelet',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BRACELET,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::BRACELET)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted ring',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_RING,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::RING)
                ],
                'enchantments' => []
            ],
            [
                'create_array' => [
                    'name' => null,
                    'description' => 'Random enchanted crown',
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_CROWN,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                    'item_bases' => $itemBases->where('name', '=', ItemBase::CROWN)
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
