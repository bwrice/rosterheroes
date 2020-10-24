<?php

namespace App\StorableEvents;

use App\Domain\Models\Minion;
use Illuminate\Queue\SerializesModels;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class SideQuestMinionKillsHero extends ShouldBeStored
{
    use SerializesModels;

    /**
     * @var Minion
     */
    public $minion;

    public function __construct(Minion $minion)
    {
        $this->minion = $minion;
    }
}
