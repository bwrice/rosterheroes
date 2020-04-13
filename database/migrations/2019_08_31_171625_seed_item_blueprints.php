<?php

use App\Domain\Models\Attack;
use App\Domain\Models\Enchantment;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use Illuminate\Database\Eloquent\Collection;
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

        $blueprints = collect([

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
                    'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ITEM,
                    'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                ],
                'enchantments' => []
            ],
        ]);

        $blueprints = $blueprints->union($this->getRandomItemBaseBlueprintArrays($itemBases, $itemClasses));

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

    protected function getRandomItemBaseBlueprintArrays(Collection $itemBases, Collection $itemClasses)
    {
        $referenceBasePairs = [
            ItemBlueprint::RANDOM_ENCHANTED_DAGGER => ItemBase::DAGGER,
            ItemBlueprint::RANDOM_ENCHANTED_SWORD => ItemBase::SWORD,
            ItemBlueprint::RANDOM_ENCHANTED_AXE => ItemBase::AXE,
            ItemBlueprint::RANDOM_ENCHANTED_MACE => ItemBase::MACE,
            ItemBlueprint::RANDOM_ENCHANTED_BOW => ItemBase::BOW,
            ItemBlueprint::RANDOM_ENCHANTED_CROSSBOW => ItemBase::CROSSBOW,
            ItemBlueprint::RANDOM_ENCHANTED_THROWING_WEAPON => ItemBase::THROWING_WEAPON,
            ItemBlueprint::RANDOM_ENCHANTED_POLEARM => ItemBase::POLEARM,
            ItemBlueprint::RANDOM_ENCHANTED_TWO_HAND_SWORD => ItemBase::TWO_HAND_SWORD,
            ItemBlueprint::RANDOM_ENCHANTED_TWO_HAND_AXE => ItemBase::TWO_HAND_AXE,
            ItemBlueprint::RANDOM_ENCHANTED_WAND => ItemBase::WAND,
            ItemBlueprint::RANDOM_ENCHANTED_ORB => ItemBase::ORB,
            ItemBlueprint::RANDOM_ENCHANTED_STAFF => ItemBase::STAFF,
            ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_ONE_HAND => ItemBase::PSIONIC_ONE_HAND,
            ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_TWO_HAND => ItemBase::PSIONIC_TWO_HAND,
            ItemBlueprint::RANDOM_ENCHANTED_SHIELD => ItemBase::SHIELD,
            ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_SHIELD => ItemBase::PSIONIC_SHIELD,
            ItemBlueprint::RANDOM_ENCHANTED_HELMET => ItemBase::HELMET,
            ItemBlueprint::RANDOM_ENCHANTED_CAP => ItemBase::CAP,
            ItemBlueprint::RANDOM_ENCHANTED_HEAVY_ARMOR => ItemBase::HEAVY_ARMOR,
            ItemBlueprint::RANDOM_ENCHANTED_LIGHT_ARMOR => ItemBase::LIGHT_ARMOR,
            ItemBlueprint::RANDOM_ENCHANTED_LEGGINGS => ItemBase::LEGGINGS,
            ItemBlueprint::RANDOM_ENCHANTED_ROBES => ItemBase::ROBES,
            ItemBlueprint::RANDOM_ENCHANTED_GLOVES => ItemBase::GLOVES,
            ItemBlueprint::RANDOM_ENCHANTED_GAUNTLETS => ItemBase::GAUNTLETS,
            ItemBlueprint::RANDOM_ENCHANTED_SHOES => ItemBase::SHOES,
            ItemBlueprint::RANDOM_ENCHANTED_BOOTS => ItemBase::BOOTS,
            ItemBlueprint::RANDOM_ENCHANTED_BELT => ItemBase::BELT,
            ItemBlueprint::RANDOM_ENCHANTED_SASH => ItemBase::SASH,
            ItemBlueprint::RANDOM_ENCHANTED_NECKLACE => ItemBase::NECKLACE,
            ItemBlueprint::RANDOM_ENCHANTED_BRACELET => ItemBase::BRACELET,
            ItemBlueprint::RANDOM_ENCHANTED_RING => ItemBase::RING,
            ItemBlueprint::RANDOM_ENCHANTED_CROWN => ItemBase::CROWN,
        ];

        return collect($referenceBasePairs)->map(function ($itemBase, $blueprintReference) use ($itemBases, $itemClasses) {
            return $this->getCreateArrayForRandomItemBase($blueprintReference, $itemBase, $itemBases, $itemClasses);
        })->toArray();
    }

    protected function getCreateArrayForRandomItemBase(string $blueprintReferenceID, string $itemBaseName, Collection $itemClasses, Collection $itemBases)
    {
        return [
            'create_array' => [
                'name' => null,
                'description' => 'Random enchanted '. $itemBaseName,
                'reference_id' => $blueprintReferenceID,
                'item_classes' => $itemClasses->where('name', '=', ItemClass::ENCHANTED),
                'item_bases' => $itemBases->where('name', '=', $itemBaseName)
            ],
            'enchantments' => []
        ];
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
