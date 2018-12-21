<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/16/18
 * Time: 5:16 PM
 */

namespace App\Slots;


interface HasSlots
{
    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection;

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots;

    /**
     * @param array $with
     * @return HasSlots
     */
    public function getFresh($with = []): HasSlots;

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlottableCollection
     */
    public function emptySlots(int $count, array $slotTypeIDs = []): SlottableCollection;
}