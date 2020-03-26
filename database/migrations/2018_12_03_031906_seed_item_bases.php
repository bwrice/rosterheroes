<?php

use App\Domain\Models\ItemBase;
use App\Domain\Models\MaterialType;
use Illuminate\Database\Migrations\Migration;

class SeedItemBases extends Migration
{
    public function up()
    {
        $data = [
            [
                'name' => ItemBase::DAGGER,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL,
                    MaterialType::GEMSTONE,
                ]
            ],
            [
                'name' => ItemBase::SWORD,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL
                ]
            ],
            [
                'name' => ItemBase::AXE,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL
                ]
            ],
            [
                'name' => ItemBase::MACE,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL
                ]
            ],
            [
                'name' => ItemBase::BOW,
                'material_types' => [
                    MaterialType::WOOD
                ]
            ],
            [
                'name' => ItemBase::CROSSBOW,
                'material_types' => [
                    MaterialType::WOOD
                ]
            ],
            [
                'name' => ItemBase::THROWING_WEAPON,
                'material_types' => [
                    MaterialType::WOOD,
                    MaterialType::METAL,
                ]
            ],
            [
                'name' => ItemBase::POLEARM,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL
                ]
            ],
            [
                'name' => ItemBase::TWO_HAND_SWORD,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL
                ]
            ],
            [
                'name' => ItemBase::TWO_HAND_AXE,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL
                ]
            ],
            [
                'name' => ItemBase::WAND,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::GEMSTONE,
                    MaterialType::WOOD
                ]
            ],
            [
                'name' => ItemBase::ORB,
                'material_types' => [
                    MaterialType::GEMSTONE
                ]
            ],
            [
                'name' => ItemBase::STAFF,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::GEMSTONE,
                    MaterialType::WOOD
                ]
            ],
            [
                'name' => ItemBase::PSIONIC_ONE_HAND,
                'material_types' => [
                    MaterialType::PSIONIC
                ]
            ],
            [
                'name' => ItemBase::PSIONIC_TWO_HAND,
                'material_types' => [
                    MaterialType::PSIONIC
                ]
            ],
            [
                'name' => ItemBase::SHIELD,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL
                ]
            ],
            [
                'name' => ItemBase::PSIONIC_SHIELD,
                'material_types' => [
                    MaterialType::PSIONIC
                ]
            ],
            [
                'name' => ItemBase::HELMET,
                'material_types' => [
                    MaterialType::BONE,
                    MaterialType::METAL
                ]
            ],
            [
                'name' => ItemBase::CAP,
                'material_types' => [
                    MaterialType::CLOTH,
                    MaterialType::HIDE
                ]
            ],
            [
                'name' => ItemBase::HEAVY_ARMOR,
                'material_types' => [
                    MaterialType::METAL,
                    MaterialType::HIDE
                ]
            ],
            [
                'name' => ItemBase::LIGHT_ARMOR,
                'material_types' => [
                    MaterialType::METAL,
                    MaterialType::HIDE
                ]
            ],
            [
                'name' => ItemBase::LEGGINGS,
                'material_types' => [
                    MaterialType::CLOTH,
                    MaterialType::HIDE
                ]
            ],
            [
                'name' => ItemBase::ROBES,
                'material_types' => [
                    MaterialType::CLOTH,
                ]
            ],
            [
                'name' => ItemBase::GLOVES,
                'material_types' => [
                    MaterialType::CLOTH,
                    MaterialType::HIDE
                ]
            ],
            [
                'name' => ItemBase::GAUNTLETS,
                'material_types' => [
                    MaterialType::METAL,
                    MaterialType::HIDE
                ]
            ],
            [
                'name' => ItemBase::SHOES,
                'material_types' => [
                    MaterialType::CLOTH,
                ]
            ],
            [
                'name' => ItemBase::BOOTS,
                'material_types' => [
                    MaterialType::METAL,
                    MaterialType::HIDE
                ]
            ],
            [
                'name' => ItemBase::BELT,
                'material_types' => [
                    MaterialType::METAL,
                    MaterialType::HIDE
                ]
            ],
            [
                'name' => ItemBase::SASH,
                'material_types' => [
                    MaterialType::CLOTH,
                ]
            ],
            [
                'name' => ItemBase::NECKLACE,
                'material_types' => [
                    MaterialType::GEMSTONE,
                    MaterialType::PRECIOUS_METAL,
                ]
            ],
            [
                'name' => ItemBase::BRACELET,
                'material_types' => [
                    MaterialType::GEMSTONE,
                    MaterialType::PRECIOUS_METAL,
                ]
            ],
            [
                'name' => ItemBase::RING,
                'material_types' => [
                    MaterialType::GEMSTONE,
                    MaterialType::PRECIOUS_METAL,
                ]
            ],
            [
                'name' => ItemBase::CROWN,
                'material_types' => [
                    MaterialType::GEMSTONE,
                    MaterialType::PRECIOUS_METAL,
                ]
            ],
        ];

        $materialTypes = MaterialType::all();

        foreach ($data as $itemBaseData) {
            /** @var ItemBase $itemBase */
            $itemBase = ItemBase::query()->create([
                'name' => $itemBaseData['name']
            ]);

            $typesToAttach = $materialTypes->filter(function (MaterialType $materialType) use ($itemBaseData) {
                return in_array($materialType->name, $itemBaseData['material_types']);
            });

            $itemBase->materialTypes()->saveMany($typesToAttach);
        }
    }
}
