<?php

use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemGroup;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SlotType;
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
        $itemGroups = ItemGroup::all();
        $measurableTypes = MeasurableType::all();
        $slotTypes = SlotType::all();

        $itemBases = [
            [
                'name' => ItemBase::DAGGER,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::AGILITY,
                    MeasurableType::FOCUS
                ]
            ],
            [
                'name' => ItemBase::SWORD,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::VALOR,
                    MeasurableType::AGILITY
                ]
            ],
            [
                'name' => ItemBase::AXE,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR
                ]
            ],
            [
                'name' => ItemBase::MACE,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::AGILITY
                ]
            ],
            [
                'name' => ItemBase::BOW,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::AGILITY,
                    MeasurableType::FOCUS
                ]
            ],
            [
                'name' => ItemBase::CROSSBOW,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::AGILITY,
                    MeasurableType::FOCUS
                ]
            ],
            [
                'name' => ItemBase::THROWING_WEAPON,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::FOCUS
                ]
            ],
            [
                'name' => ItemBase::POLE_ARM,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::AGILITY,
                    MeasurableType::FOCUS
                ]
            ],
            [
                'name' => ItemBase::TWO_HAND_SWORD,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR,
                    MeasurableType::AGILITY
                ]
            ],
            [
                'name' => ItemBase::TWO_HAND_AXE,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::VALOR
                ]
            ],
            [
                'name' => ItemBase::WAND,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::FOCUS,
                    MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => ItemBase::ORB,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::FOCUS,
                    MeasurableType::APTITUDE
                ]
            ],
            [
                'name' => ItemBase::STAFF,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::FOCUS,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => ItemBase::PSIONIC_ONE_HAND,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::AGILITY,
                    MeasurableType::APTITUDE,
                    MeasurableType::INTELLIGENCE
                ]
            ],
            [
                'name' => ItemBase::PSIONIC_TWO_HAND,
                'group' => ItemGroup::WEAPON,
                'slot_types' => [
                    SlotType::RIGHT_ARM,
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [
                    MeasurableType::STRENGTH,
                    MeasurableType::FOCUS,
                    MeasurableType::APTITUDE
                ]
            ],
            [
                'name' => ItemBase::SHIELD,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::PSIONIC_SHIELD,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::LEFT_ARM
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::HELMET,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::CAP,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::EYE_WEAR,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::HEAD
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::HEAVY_ARMOR,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::LIGHT_ARMOR,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::ROBES,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::TORSO
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::GLOVES,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::HANDS
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::GAUNTLETS,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::HANDS
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::SHOES,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::FEET
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::BOOTS,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::FEET
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::BELT,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::WAIST
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::SASH,
                'group' => ItemGroup::ARMOR,
                'slot_types' => [
                    SlotType::WAIST
                ],
                'measurable types' => [

                ]
            ],
            [
                'name' => ItemBase::NECKLACE,
                'group' => ItemGroup::JEWELRY,
                'slot_types' => [
                    SlotType::NECK
                ],
                'measurable types' => [
                    MeasurableType::HEALTH
                ]
            ],
            [
                'name' => ItemBase::BRACELET,
                'group' => ItemGroup::JEWELRY,
                'slot_types' => [
                    SlotType::LEFT_WRIST,
                    SlotType::RIGHT_WRIST
                ],
                'measurable types' => [
                    MeasurableType::STAMINA
                ]
            ],
            [
                'name' => ItemBase::RING,
                'group' => ItemGroup::JEWELRY,
                'slot_types' => [
                    SlotType::LEFT_RING,
                    SlotType::RIGHT_RING
                ],
                'measurable types' => [
                    MeasurableType::MANA
                ]
            ],
            [
                'name' => ItemBase::CROWN,
                'group' => ItemGroup::JEWELRY,
                'slot_types' => [
                    SlotType::HEAD
                ],
                'measurable types' => [
                    MeasurableType::HEALTH
                ]
            ],
        ];

        foreach ( $itemBases as $itemBase ) {

            $itemGroup = $itemGroups->where( 'name', $itemBase['group'] )->first();
            $itemBaseCreated = $itemGroup->itemBases()->create( [
                'name' => $itemBase['name'],
            ] );

            $measurableTypesToSave = $measurableTypes->whereIn( 'name', $itemBase['measurable types'] );
            /** @var ItemBase $itemBaseCreated */
            $itemBaseCreated->measurableTypes()->saveMany( $measurableTypesToSave );

            //Add universal and wagon slot type to all current item bases
            $itemBase['slot_types'][] = SlotType::UNIVERSAL;
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
        ItemBase::query()->delete();
    }
}
