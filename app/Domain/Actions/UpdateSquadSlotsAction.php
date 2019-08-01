<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/15/19
 * Time: 3:13 PM
 */

namespace App\Domain\Actions;


use App\Aggregates\SquadAggregate;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;

class UpdateSquadSlotsAction
{
    /**
     * @param Squad $squad
     */
    public function execute(Squad $squad)
    {
        $slotsNeededCount = $squad->mobileStorageRank->getBehavior()->getSlotsCount();
        $currentSlotsCount = $squad->slots()->count();
        $diff = $slotsNeededCount - $currentSlotsCount;

        if ($diff > 0) {
            /** @var SquadAggregate $aggregate */
            $aggregate = SquadAggregate::retrieve($squad->uuid);
            $aggregate->addSlots(SlotType::UNIVERSAL, $diff);
            $aggregate->persist();
        }
    }
}