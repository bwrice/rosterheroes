<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedItemBases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $itemGroups = \App\ItemGroup::all();
        $measurableTypes = \App\MeasurableType::all();
        $slotTypes = \App\SlotType::all();

        $itemBases = [
            [
                'name' => \App\Items\ItemBases\ItemBase::DAGGER,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::AGILITY,
                    \App\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::SWORD,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::VALOR,
                    \App\MeasurableType::AGILITY
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::AXE,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::STRENGTH,
                    \App\MeasurableType::VALOR
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::MACE,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::STRENGTH,
                    \App\MeasurableType::AGILITY
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::BOW,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::AGILITY,
                    \App\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::CROSSBOW,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::STRENGTH,
                    \App\MeasurableType::AGILITY,
                    \App\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::THROWING_WEAPON,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::STRENGTH,
                    \App\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::POLE_ARM,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::STRENGTH,
                    \App\MeasurableType::AGILITY,
                    \App\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::TWO_HAND_SWORD,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::STRENGTH,
                    \App\MeasurableType::VALOR,
                    \App\MeasurableType::AGILITY
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::TWO_HAND_AXE,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::STRENGTH,
                    \App\MeasurableType::VALOR
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::WAND,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::FOCUS,
                    \App\MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::ORB,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::FOCUS,
                    \App\MeasurableType::APTITUDE
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::STAFF,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::FOCUS,
                    \App\MeasurableType::APTITUDE,
                    \App\MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::PSIONIC_ONE_HAND,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::AGILITY,
                    \App\MeasurableType::APTITUDE,
                    \App\MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::PSIONIC_TWO_HAND,
                'group' => \App\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\SlotType::RIGHT_ARM,
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\MeasurableType::STRENGTH,
                    \App\MeasurableType::FOCUS,
                    \App\MeasurableType::APTITUDE
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::SHIELD,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::PSIONIC_SHIELD,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::HELMET,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::CAP,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::EYE_WEAR,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::HEAVY_ARMOR,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::LIGHT_ARMOR,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::ROBES,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::GLOVES,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HANDS
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::GAUNTLETS,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HANDS
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::SHOES,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::FEET
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::BOOTS,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::FEET
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::BELT,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::WAIST
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::SASH,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::WAIST
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::NECKLACE,
                'group' => \App\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\SlotType::NECK
                ],
                'measurable types' => [
                    \App\MeasurableType::HEALTH
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::BRACELET,
                'group' => \App\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\SlotType::LEFT_WRIST,
                    \App\SlotType::RIGHT_WRIST
                ],
                'measurable types' => [
                    \App\MeasurableType::STAMINA
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::RING,
                'group' => \App\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\SlotType::LEFT_RING,
                    \App\SlotType::RIGHT_RING
                ],
                'measurable types' => [
                    \App\MeasurableType::MANA
                ]
            ],
            [
                'name' => \App\Items\ItemBases\ItemBase::CROWN,
                'group' => \App\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\SlotType::HEAD
                ],
                'measurable types' => [
                    \App\MeasurableType::HEALTH
                ]
            ],
        ];

        foreach ( $itemBases as $itemBase ) {

            $itemGroup = $itemGroups->where( 'name', $itemBase['group'] )->first();
            $itemBaseCreated = $itemGroup->itemBases()->create( [
                'name' => $itemBase['name'],
            ] );

            $measurableTypesToSave = $measurableTypes->whereIn( 'name', $itemBase['measurable types'] );
            /** @var \App\Items\ItemBases\ItemBase $itemBaseCreated */
            $itemBaseCreated->measurableTypes()->saveMany( $measurableTypesToSave );

            //Add universal and wagon slot type to all current item bases
            $itemBase['slot_types'][] = \App\SlotType::UNIVERSAL;
            $itemBase['slot_types'][] = \App\SlotType::WAGON;
            $slotTypesToSave = $slotTypes->whereIn( 'name', $itemBase['slot_types'] );
            $itemBaseCreated->slotTypes()->saveMany( $slotTypesToSave );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('item_base_slot_type')->truncate();
        \Illuminate\Support\Facades\DB::table('item_base_measurable_type')->truncate();
        \App\Items\ItemBases\ItemBase::query()->delete();
    }
}
