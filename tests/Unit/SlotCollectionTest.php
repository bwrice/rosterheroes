<?php

namespace Tests\Unit;

use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Slot;
use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\Slottable;
use App\Domain\Models\SlotType;
use App\Wagons\Wagon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotCollectionTest
{
//
//    use DatabaseTransactions;
//
//    /** @var SlotCollection $slotCollection */
//    protected $slotCollection;
//
//    public function setUp(): void
//    {
//        $this->slotCollection = new SlotCollection();
//        parent::setUp();
//    }

//    /**
//     * @test
//     * @dataProvider provides_it_can_empty_slottables
//     *
//     * @param $hasSlotsObject
//     * @param $slotsToFillAndEmptyArrays
//     * @param $alreadyEmptySlots
//     * @param $extraSlotsToFillArray
//     * @param $slotsToEmptyCount
//     * @param $expectsException
//     */
//    public function it_can_empty_items($hasSlotsObject, $slotsToFillAndEmptyArrays, $alreadyEmptySlots = [], $extraSlotsToFillArray = [], $slotsToEmptyCount = null, $expectsException = false)
//    {
//        $totalSlotCollection = new SlotCollection();
//        $filledSlotCollection = new SlotCollection();
//        $slotsToFillAndEmptyCollection = new SlotCollection();
//        $fillAndKeepFilledCollection = new SlotCollection();
//        $filledSlotsCount = 0;
//        $slotsToFillAndEmptyCount = 0;
//        $slotsToFillAndKeepFilledCount = 0;
//        $totalSlotsCount = 0;
//        $slottablesToEmptyCount = 0;
//
//        $hasSlots = factory($hasSlotsObject['factory_class'])->create();
//        $hasSlotsType = $hasSlotsObject['has_slots_type'];
//
//        $filledSlotTypeIDs = [];
//
//        /*
//         * Fill the slots we plan to empty
//         */
//        foreach($slotsToFillAndEmptyArrays as $slotsToFill) {
//
//            $itemType = ItemType::whereHas('itemBase', function (Builder $query) use ($slotsToFill) {
//                return $query->where('name', '=', $slotsToFill['blueprint_item_base']);
//            })->inRandomOrder()->first();
//
//            /** @var ItemBlueprint $bluePrint */
//            $bluePrint = factory(ItemBlueprint::class)->create([
//                'item_type_id' => $itemType->id
//            ]);
//
//            $item = $bluePrint->generate();
//            $slottablesToEmptyCount++;
//
//            foreach($slotsToFill['slots'] as $slot) {
//
//                $slotType = SlotType::where('name', '=', $slot['type'])->first();
//                $filledSlotTypeIDs[] = $slotType->id;
//
//                for($i = 1; $i <= $slot['count']; $i++) {
//
//                    /** @var Slot $slotCreated */
//                    $slotCreated = factory(Slot::class)->create([
//                        'slot_type_id' => $slotType->id,
//                        'has_slots_type' => $hasSlotsType,
//                        'has_slots_id' => $hasSlots->id
//                    ]);
//
//                    $slotCreated->slottable()->associate($item);
//                    $this->assertEquals($slotCreated->slottable_id, $item->id, 'slottable ID is the item ID');
//
//                    $totalSlotCollection->push($slotCreated);
//                    $filledSlotCollection->push($slotCreated);
//                    $slotsToFillAndEmptyCollection->push($slotCreated);
//
//                    $totalSlotsCount++;
//                    $filledSlotsCount++;
//                    $slotsToFillAndEmptyCount++;
//                }
//            }
//        }
//
//        $this->assertEquals($totalSlotsCount, $totalSlotCollection->count(), "Slot Collection has the expected amount of total slots");
//        $this->assertEquals($filledSlotsCount, $filledSlotCollection->count(), 'Slot Collection has the expected amount of filled slots');
//        $this->assertEquals($slotsToFillAndEmptyCount, $slotsToFillAndEmptyCollection->count(), 'Slot Collection has the expected amount of slots to empty');
//
//        /*
//         * Add the extra slots to make sure we're just not emptying every slot
//         */
//        foreach($alreadyEmptySlots as $emptySlot) {
//
//            $slotType = SlotType::where('name', '=', $emptySlot['type'])->first();
//
//            for($i = 1; $i <= $emptySlot['count']; $i++) {
//
//                /** @var Slot $slotCreated */
//                $slotCreated = factory(Slot::class)->create([
//                    'slot_type_id' => $slotType->id,
//                    'has_slots_type' => $hasSlotsType,
//                    'has_slots_id' => $hasSlots->id
//                ]);
//
//                $totalSlotCollection->push($slotCreated);
//                $totalSlotsCount++;
//            }
//        }
//        $this->assertEquals($totalSlotsCount, $totalSlotCollection->count(), "Slot Collection has the expected amount of total slots");
//
//        /*
//         * Fill the even more slots that we DON'T plan to empty
//         */
//        foreach($extraSlotsToFillArray as $slotsToFill) {
//
//            $itemType = ItemType::whereHas('itemBase', function (Builder $query) use ($slotsToFill) {
//                return $query->where('name', '=', $slotsToFill['blueprint_item_base']);
//            })->inRandomOrder()->first();
//
//            /** @var ItemBlueprint $bluePrint */
//            $bluePrint = factory(ItemBlueprint::class)->create([
//                'item_type_id' => $itemType->id
//            ]);
//
//            $item = $bluePrint->generate();
//
//            foreach($slotsToFill['slots'] as $slot) {
//
//                $slotType = SlotType::where('name', '=', $slot['type'])->first();
//                $filledSlotTypeIDs[] = $slotType->id;
//
//                for($i = 1; $i <= $slot['count']; $i++) {
//
//                    /** @var Slot $slotCreated */
//                    $slotCreated = factory(Slot::class)->create([
//                        'slot_type_id' => $slotType->id,
//                        'has_slots_type' => $hasSlotsType,
//                        'has_slots_id' => $hasSlots->id
//                    ]);
//
//                    $slotCreated->slottable()->associate($item);
//                    $this->assertEquals($slotCreated->slottable_id, $item->id, 'slottable ID is the item ID');
//
//                    $totalSlotCollection->push($slotCreated);
//                    $filledSlotCollection->push($slotCreated);
//                    $fillAndKeepFilledCollection->push($slotCreated);
//
//                    $totalSlotsCount++;
//                    $filledSlotsCount++;
//                    $slotsToFillAndKeepFilledCount++;
//                }
//            }
//        }
//
//        $this->assertEquals($filledSlotsCount, $filledSlotCollection->count(), 'Slot Collection has the expected amount of filled slots');
//        $this->assertEquals($totalSlotsCount, $totalSlotCollection->count(), "Slot Collection has the expected amount of total slots");
//        $this->assertEquals($slotsToFillAndKeepFilledCount, $fillAndKeepFilledCollection->count(), "Slot Collection has the expected amount of filled and keep filled slots");
//
//        try {
//
//            // If count is passed as argument use it, otherwise use the count of the slots purposely filled to empty
//            $slotsToEmptyCount = is_integer($slotsToEmptyCount) ? $slotsToEmptyCount : $slotsToFillAndEmptyCount;
//
//            // What we're actually testing
//            $slottables = $totalSlotCollection->emptySlots($slotsToEmptyCount, $filledSlotTypeIDs);
//
//            if ($expectsException) {
//                $this->fail("Exception not thrown");
//            }
//
//            $this->assertEquals($slottablesToEmptyCount, $slottables->count());
//
//            $slottables->each(function (Slottable $slottable) {
//               $uniqueHasSlotIDs = $slottable->slots()->get()->map(function (Slot $slot) {
//                   return $slot->has_slots_id;
//               })->values()->unique();
//
//               $this->assertLessThan(2, $uniqueHasSlotIDs->count(), "Slottable belongs to none or single has slots");
//            });
//
//            $slotsToFillAndEmptyCollection->each(function (Slot $slot) {
//
//                $this->assertNull($slot->slottable_id, 'slot to fill and empty is now empty');
//            });
//
//            $fillAndKeepFilledCollection->each(function (Slot $slot) {
//
//                $this->assertNotNull($slot->slottable_id, 'keep filled slot is still filled');
//            });
//
//
//        } catch ( \RuntimeException $exception ) {
//
//            if ( ! $expectsException ) {
//                throw $exception;
//            }
//        }
//        //TODO: scenarios to test
//        /*
//         * 1) Emptying more than 1 slot
//         * 2) Emptying Multi-slot items
//         * 3) Emptying combination of multi-slot and single-slot items
//         * 4) Emptying a count > filled slots
//         */
//    }

    public function provides_it_can_empty_slottables()
    {
        return [
            'fill and empty single hands slot with already empty torso' => [
                'has_slots_object' => [
                    'factory_class' => Hero::class,
                    'has_slots_type' => Hero::RELATION_MORPH_MAP_KEY
                ],
                'slots_to_fill_and_empty_arrays' => [
                    [
                        'slots' => [
                            [
                                'type' => SlotType::HANDS,
                                'count' => 1
                            ]
                        ],
                        'blueprint_item_base' => ItemBase::GLOVES
                    ],
                ],
                'already_empty_slots' => [
                    [
                        'type' => SlotType::TORSO,
                        'count' => 1
                    ]
                ],
            ],
            'fill and empty Right Arm and Left Arm with already empty torso and feet ' => [
                'has_slots_object' => [
                    'factory_class' => Hero::class,
                    'has_slots_type' => Hero::RELATION_MORPH_MAP_KEY
                ],
                'slots_to_fill_and_empty_arrays' => [
                    [
                        'slots' => [
                            [
                                'type' => SlotType::RIGHT_ARM,
                                'count' => 1
                            ],
                            [
                                'type' => SlotType::LEFT_ARM,
                                'count' => 1
                            ]
                        ],
                        'blueprint_item_base' => ItemBase::BOW
                    ],
                ],
                'already_empty_slots' => [
                    [
                        'type' => SlotType::TORSO,
                        'count' => 1
                    ],
                    [
                        'type' => SlotType::FEET,
                        'count' => 1
                    ]
                ]
            ],
            'fill and empty right arm and left arm with already empty feet but filled torso' => [
                'has_slots_object' => [
                    'factory_class' => Hero::class,
                    'has_slots_type' => Hero::RELATION_MORPH_MAP_KEY
                ],
                'slots_to_fill_and_empty_arrays' => [
                    [
                        'slots' => [
                            [
                                'type' => SlotType::RIGHT_ARM,
                                'count' => 1
                            ],
                            [
                                'type' => SlotType::LEFT_ARM,
                                'count' => 1
                            ]
                        ],
                        'blueprint_item_base' => ItemBase::TWO_HAND_SWORD
                    ],
                ],
                'already_empty_slots' => [
                    [
                        'type' => SlotType::FEET,
                        'count' => 1
                    ]
                ],
                'slots_to_fill_arrays' => [
                    [
                        'slots' => [
                            [
                                'type' => SlotType::TORSO,
                                'count' => 1
                            ]
                        ],
                        'blueprint_item_base' => ItemBase::ROBES
                    ],
                ]
            ],
            'fill and empty head, waist, torso with already empty right arm and left arm' => [
                'has_slots_object' => [
                    'factory_class' => Hero::class,
                    'has_slots_type' => Hero::RELATION_MORPH_MAP_KEY
                ],
                'slots_to_fill_and_empty_arrays' => [
                    [
                        'slots' => [
                            [
                                'type' => SlotType::WAIST,
                                'count' => 1
                            ],
                        ],
                        'blueprint_item_base' => ItemBase::BELT
                    ],
                    [
                        'slots' => [
                            [
                                'type' => SlotType::TORSO,
                                'count' => 1
                            ],
                        ],
                        'blueprint_item_base' => ItemBase::HEAVY_ARMOR
                    ],
                    [
                        'slots' => [
                            [
                                'type' => SlotType::HEAD,
                                'count' => 1
                            ],
                        ],
                        'blueprint_item_base' => ItemBase::HELMET
                    ],
                ],
                'already_empty_slots' => [
                    [
                        'type' => SlotType::RIGHT_ARM,
                        'count' => 1
                    ],
                    [
                        'type' => SlotType::LEFT_ARM,
                        'count' => 1
                    ]
                ]
            ],
            'fill only one ring slot by try to empty 2 slots' => [
                'has_slots_object' => [
                    'factory_class' => Hero::class,
                    'has_slots_type' => Hero::RELATION_MORPH_MAP_KEY
                ],
                'slots_to_fill_and_empty_arrays' => [
                    [
                        'slots' => [
                            [
                                'type' => SlotType::RIGHT_RING,
                                'count' => 1
                            ],
                        ],
                        'blueprint_item_base' => ItemBase::RING
                    ],
                ],
                'already_empty_slots' => [],
                'slots_to_fill_arrays' => [],
                'slots_to_empty_count' => 2,
                'expects_exception' => true
            ],
            'filled arms and already empty wrist slots but attempting to empty 3 when only 2 are filled' => [
                'has_slots_object' => [
                    'factory_class' => Hero::class,
                    'has_slots_type' => Hero::RELATION_MORPH_MAP_KEY
                ],

                'slots_to_fill_and_empty_arrays' => [
                    [
                        'slots' => [
                            [
                                'type' => SlotType::RIGHT_ARM,
                                'count' => 1
                            ],
                            [
                                'type' => SlotType::LEFT_ARM,
                                'count' => 1
                            ]
                        ],
                        'blueprint_item_base' => ItemBase::POLE_ARM
                    ],
                ],
                'already_empty_slots' => [
                    [
                        'type' => SlotType::LEFT_WRIST,
                        'count' => 1
                    ],
                    [
                        'type' => SlotType::RIGHT_WRIST,
                        'count' => 1
                    ]
                ],
                'slots_to_fill_arrays' => [],
                'slots_to_empty_count' => 3,
                'expects_exception' => true
            ],
            'filled arms and already empty wrist slots but only need oen slot when two are filled by same item' => [
                'has_slots_object' => [
                    'factory_class' => Hero::class,
                    'has_slots_type' => Hero::RELATION_MORPH_MAP_KEY
                ],

                'slots_to_fill_and_empty_arrays' => [
                    [
                        'slots' => [
                            [
                                'type' => SlotType::RIGHT_ARM,
                                'count' => 1
                            ],
                            [
                                'type' => SlotType::LEFT_ARM,
                                'count' => 1
                            ]
                        ],
                        'blueprint_item_base' => ItemBase::PSIONIC_TWO_HAND
                    ],
                ],
                'already_empty_slots' => [
                    [
                        'type' => SlotType::LEFT_WRIST,
                        'count' => 1
                    ],
                    [
                        'type' => SlotType::RIGHT_WRIST,
                        'count' => 1
                    ]
                ],
                'slots_to_fill_arrays' => [],
                'slots_to_empty_count' => 1,
                'expects_exception' => true
            ],
//            'multiple filled wagon slots' => [
//                'has_slots_object' => [
//                    'factory_class' => Wagon::class,
//                    'has_slots_type' => Wagon::RELATION_MORPH_MAP_KEY
//                ],
//                'slots_to_fill_and_empty_arrays' => [
//                    [
//                        'slots' => [
//                            [
//                                'type' => SlotType::WAGON,
//                                'count' => 2
//                            ],
//                        ],
//                        'blueprint_item_base' => ItemBase::STAFF
//                    ],
//                    [
//                        'slots' => [
//                            [
//                                'type' => SlotType::WAGON,
//                                'count' => 2
//                            ],
//                        ],
//                        'blueprint_item_base' => ItemBase::TWO_HAND_AXE
//                    ],
//                    [
//                        'slots' => [
//                            [
//                                'type' => SlotType::WAGON,
//                                'count' => 1
//                            ],
//                        ],
//                        'blueprint_item_base' => ItemBase::ORB
//                    ],
//                ],
//                'already_empty_slots' => [
//                    [
//                        'type' => SlotType::WAGON,
//                        'count' => 1
//                    ],
//                    [
//                        'type' => SlotType::WAGON,
//                        'count' => 1
//                    ]
//                ]
//            ]
        ];
    }

//    /**
//     * @test
//     */
//    public function it_will_remove_multi_slot_items_when_requesting_to_empty_a_single_slot()
//    {
//        /** @var ItemBlueprint $bluePrint */
//        $bluePrint = factory(ItemBlueprint::class)->create([
//            'item_type_id' => null,
//            'item_base_id' => ItemBase::where('name', '=', ItemBase::TWO_HAND_SWORD)->first()->id
//        ]);
//
//        $item = $bluePrint->generate();
//
//
//        $slotType = SlotType::where('name', '=', SlotType::LEFT_ARM)->first();
//        /** @var Slot $slotCreated */
//        $slotCreated = factory(Slot::class)->create([
//            'slot_type_id' => $slotType->id,
//            'slottable_id' => $item->id,
//            'slottable_type' => Item::RELATION_MORPH_MAP
//        ]);
//
//        $this->slotCollection->push($slotCreated);
//
//        $slotType = SlotType::where('name', '=', SlotType::RIGHT_ARM)->first();
//        /** @var Slot $slotCreated */
//        $slotCreated = factory(Slot::class)->create([
//            'slot_type_id' => $slotType->id,
//            'slottable_id' => $item->id,
//            'slottable_type' => Item::RELATION_MORPH_MAP
//        ]);
//
//        $this->slotCollection->push($slotCreated);
//
//        $item = $item->fresh();
//
//        $this->assertEquals(2, $item->slots->count(), "Item belongs to multiple slots");
//
//        $slottables = $this->slotCollection->emptySlots(1, [$slotType->id]);
//
//        $this->assertEquals(1, $slottables->count());
//        $this->assertEquals(2, $this->slotCollection->slotEmpty()->count());
//    }
}
