<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedTierOneShopItemBlueprints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $blueprints = [
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_DAGGER,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Knife',
                    'Kris'
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_SWORD,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Short Sword',
                    'Falchion'
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_AXE,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Hatchet',
                    'Hand Axe'
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_BOW,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Straight Bow',
                    'Longbow',
                ],
                'materials' => [
                    'Yew',
                    'Juniper',
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_POLEARM,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Spear',
                    'Glaive'
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_WAND,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Sprig',
                    'Lesser Wand',
                ],
                'materials' => [
                    'Yew',
                    'Juniper',
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_STAFF,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Lesser Staff',
                    'Rod',
                ],
                'materials' => [
                    'Yew',
                    'Juniper',
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_SHIELD,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Buckler',
                    'Rondache',
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_HELMET,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Skullcap',
                    'Kettle Hat',
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_HEAVY_ARMOR,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Breastplate',
                    'Chainmail',
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_LIGHT_ARMOR,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Light Cuirass',
                    'Plackart',
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_ROBES,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Frock',
                    'Coat',
                ],
                'materials' => [
                    'Cotton',
                    'Linen'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_GLOVES,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Light Gloves',
                ],
                'materials' => [
                    'Cotton',
                    'Linen'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_GAUNTLETS,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Light Gauntlets',
                ],
                'materials' => [
                    'Copper',
                    'Iron'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_SHOES,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Slippers',
                    'Clogs',
                ],
                'materials' => [
                    'Cotton',
                    'Linen'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_BOOTS,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Light Boots'
                ],
                'materials' => [
                    'Wolf Pelt',
                    'Leather'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_BELT,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Light Belt'
                ],
                'materials' => [
                    'Wolf Pelt',
                    'Leather'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_SASH,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Pupil\'s Sash',
                    'Apprentice\'s Sash',
                ],
                'materials' => [
                    'Cotton',
                    'Linen'
                ]
            ],
            [
                'reference_id' => \App\Domain\Models\ItemBlueprint::GENERIC_LOW_TIER_LEGGINGS,
                'item_classes' => [
                    \App\Domain\Models\ItemClass::GENERIC
                ],
                'item_types' => [
                    'Greaves',
                    'Tassets',
                ],
                'materials' => [
                    'Wolf Pelt',
                    'Leather'
                ]
            ],
        ];

        /** @var \App\Domain\Actions\CreateItemBlueprint $action */
        $action = app(\App\Domain\Actions\CreateItemBlueprint::class);

        collect($blueprints)->each(function ($blueprint) use ($action) {
            $action->execute(
                $blueprint['reference_id'],
                null,
                $blueprint['item_types'],
                $blueprint['materials'],
                $blueprint['item_classes']
            );
        });
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
