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
        $itemGroups = \App\Domain\Models\ItemGroup::all();
        $measurableTypes = \App\Domain\Models\MeasurableType::all();
        $slotTypes = \App\Domain\Models\SlotType::all();

        $itemBases = [
            [
                'name' => \App\Domain\Models\ItemBase::DAGGER,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::AGILITY,
                    \App\Domain\Models\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::SWORD,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::VALOR,
                    \App\Domain\Models\MeasurableType::AGILITY
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::AXE,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STRENGTH,
                    \App\Domain\Models\MeasurableType::VALOR
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::MACE,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STRENGTH,
                    \App\Domain\Models\MeasurableType::AGILITY
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::BOW,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::AGILITY,
                    \App\Domain\Models\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::CROSSBOW,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STRENGTH,
                    \App\Domain\Models\MeasurableType::AGILITY,
                    \App\Domain\Models\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::THROWING_WEAPON,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STRENGTH,
                    \App\Domain\Models\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::POLE_ARM,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STRENGTH,
                    \App\Domain\Models\MeasurableType::AGILITY,
                    \App\Domain\Models\MeasurableType::FOCUS
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::TWO_HAND_SWORD,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STRENGTH,
                    \App\Domain\Models\MeasurableType::VALOR,
                    \App\Domain\Models\MeasurableType::AGILITY
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::TWO_HAND_AXE,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STRENGTH,
                    \App\Domain\Models\MeasurableType::VALOR
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::WAND,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::FOCUS,
                    \App\Domain\Models\MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::ORB,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::FOCUS,
                    \App\Domain\Models\MeasurableType::APTITUDE
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::STAFF,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::FOCUS,
                    \App\Domain\Models\MeasurableType::APTITUDE,
                    \App\Domain\Models\MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::PSIONIC_ONE_HAND,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::AGILITY,
                    \App\Domain\Models\MeasurableType::APTITUDE,
                    \App\Domain\Models\MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::PSIONIC_TWO_HAND,
                'group' => \App\Domain\Models\ItemGroup::WEAPON,
                'slot_types' => [
                    \App\Domain\Models\SlotType::RIGHT_ARM,
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STRENGTH,
                    \App\Domain\Models\MeasurableType::FOCUS,
                    \App\Domain\Models\MeasurableType::APTITUDE
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::SHIELD,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::PSIONIC_SHIELD,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::LEFT_ARM
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::HELMET,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::CAP,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::EYE_WEAR,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::HEAVY_ARMOR,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::LIGHT_ARMOR,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::ROBES,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::GLOVES,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::HANDS
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::GAUNTLETS,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::HANDS
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::SHOES,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::FEET
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::BOOTS,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::FEET
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::BELT,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::WAIST
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::SASH,
                'group' => \App\Domain\Models\ItemGroup::ARMOR,
                'slot_types' => [
                    \App\Domain\Models\SlotType::WAIST
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::NECKLACE,
                'group' => \App\Domain\Models\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\Domain\Models\SlotType::NECK
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::HEALTH
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::BRACELET,
                'group' => \App\Domain\Models\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\Domain\Models\SlotType::LEFT_WRIST,
                    \App\Domain\Models\SlotType::RIGHT_WRIST
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::STAMINA
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::RING,
                'group' => \App\Domain\Models\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\Domain\Models\SlotType::LEFT_RING,
                    \App\Domain\Models\SlotType::RIGHT_RING
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::MANA
                ]
            ],
            [
                'name' => \App\Domain\Models\ItemBase::CROWN,
                'group' => \App\Domain\Models\ItemGroup::JEWELRY,
                'slot_types' => [
                    \App\Domain\Models\SlotType::HEAD
                ],
                'measurable types' => [
                    \App\Domain\Models\MeasurableType::HEALTH
                ]
            ],
        ];

        foreach ( $itemBases as $itemBase ) {

            $itemGroup = $itemGroups->where( 'name', $itemBase['group'] )->first();
            $itemBaseCreated = $itemGroup->itemBases()->create( [
                'name' => $itemBase['name'],
            ] );

            $measurableTypesToSave = $measurableTypes->whereIn( 'name', $itemBase['measurable types'] );
            /** @var \App\Domain\Models\ItemBase $itemBaseCreated */
            $itemBaseCreated->measurableTypes()->saveMany( $measurableTypesToSave );

            //Add universal and wagon slot type to all current item bases
            $itemBase['slot_types'][] = \App\Domain\Models\SlotType::UNIVERSAL;
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
        \App\Domain\Models\ItemBase::query()->delete();
    }
}
