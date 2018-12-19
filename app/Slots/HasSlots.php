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
     * @param array $slotTypeIDs
     * @param int $count
     * @return SlotCollection
     */
    public function getEmptySlots( array $slotTypeIDs, int $count ): SlotCollection;

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots;

    /**
     * @return HasSlots
     */
    public function fresh(): HasSlots;

    /**
     * @param array $slotTypeIDs
     * @param int $count
     * @return SlottableCollection
     */
    public function emptySlots( array $slotTypeIDs, int $count ): SlottableCollection;
}