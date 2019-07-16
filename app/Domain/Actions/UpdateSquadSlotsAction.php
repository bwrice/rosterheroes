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
     * @var string
     */
    private $squadUuid;

    public function __construct(string $squadUuid)
    {
        $this->squadUuid = $squadUuid;
    }

    public function __invoke()
    {
        $squad = Squad::uuid($this->squadUuid);
        $slotsNeededCount = $squad->mobileStorageRank->getBehavior()->getSlotsCount();
        $currentSlotsCount = $squad->slots()->count();
        $diff = $slotsNeededCount - $currentSlotsCount;

        if ($diff > 0) {
            /** @var SquadAggregate $aggregate */
            $aggregate = SquadAggregate::retrieve($this->squadUuid);
            $aggregate->addSlots(SlotType::UNIVERSAL, $diff);
            $aggregate->persist();
        }
    }
}