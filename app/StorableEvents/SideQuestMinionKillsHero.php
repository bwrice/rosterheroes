<?php

namespace App\StorableEvents;

use App\Domain\Models\Minion;
use Illuminate\Queue\SerializesModels;
use Spatie\EventSourcing\ShouldBeStored;

final class SideQuestMinionKillsHero implements ShouldBeStored
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
