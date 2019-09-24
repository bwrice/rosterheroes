<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/16/18
 * Time: 5:16 PM
 */

namespace App\Domain\Interfaces;


use App\Domain\Collections\SlotCollection;

interface HasSlots extends HasUniqueIdentifier
{
    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return \App\Domain\Collections\SlotCollection
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

//    /**
//     * @param int $count
//     * @param array $slotTypeIDs
//     * @return SlottableCollection
//     */
//    public function emptySlots(int $count, array $slotTypeIDs = []): SlottableCollection;

    /**
     * @return SlotCollection
     */
    public function getSlots(): SlotCollection;
}
