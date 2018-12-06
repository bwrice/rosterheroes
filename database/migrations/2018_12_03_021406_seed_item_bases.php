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
                'name' => \App\ItemBase::DAGGER,
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
                'name' => \App\ItemBase::SWORD,
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
                'name' => \App\ItemBase::AXE,
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
                'name' => \App\ItemBase::MACE,
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
                'name' => \App\ItemBase::BOW,
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
                'name' => \App\ItemBase::CROSSBOW,
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
                'name' => \App\ItemBase::THROWING_WEAPON,
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
                'name' => \App\ItemBase::POLE_ARM,
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
                'name' => \App\ItemBase::TWO_HAND_SWORD,
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
                'name' => \App\ItemBase::TWO_HAND_AXE,
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
                'name' => \App\ItemBase::WAND,
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
                'name' => \App\ItemBase::ORB,
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
                'name' => \App\ItemBase::STAFF,
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
                'name' => \App\ItemBase::PSIONIC_ONE_HAND,
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
                'name' => \App\ItemBase::PSIONIC_TWO_HAND,
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
                'name' => \App\ItemBase::SHIELD,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::PSIONIC_SHIELD,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::LEFT_ARM
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::HELMET,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::CAP,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::EYE_WEAR,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::HEAVY_ARMOR,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::LIGHT_ARMOR,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::ROBES,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::GLOVES,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HANDS
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::GAUNTLETS,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::HANDS
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::SHOES,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::FEET
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::BOOTS,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::FEET
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::BELT,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::WAIST
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::SASH,
                'group' => \App\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\SlotType::WAIST
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\ItemBase::NECKLACE,
                'group' => \App\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\SlotType::NECK
                ],
                'measurable types' => [
                    \App\MeasurableType::HEALTH
                ]
            ],
            [
                'name' => \App\ItemBase::BRACELET,
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
                'name' => \App\ItemBase::RING,
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
                'name' => \App\ItemBase::CROWN,
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
            /** @var \App\ItemBase $itemBaseCreated */
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
        \App\ItemBase::query()->delete();
    }
}
