<?php

namespace App\StorableEvents;

use App\Domain\Models\SideQuest;
use Illuminate\Queue\SerializesModels;
use Spatie\EventSourcing\ShouldBeStored;

final class SquadDefeatedInSideQuest implements ShouldBeStored
{
    use SerializesModels;

    /**
     * @var SideQuest
     */
    public $sideQuest;

    public function __construct(SideQuest $sideQuest)
    {
        $this->sideQuest = $sideQuest;
    }
}
