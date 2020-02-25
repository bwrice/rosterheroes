<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class MinionBlocksHeroSideQuestEvent implements ShouldBeStored
{
    /**
     * @var int
     */
    public $sideQuestResultID;
    /**
     * @var int
     */
    public $moment;
    /**
     * @var string
     */
    public $heroUuid;
    /**
     * @var int
     */
    public $attackUuid;
    /**
     * @var string
     */
    public $itemUuid;
    /**
     * @var string
     */
    public $minionUuid;

    public function __construct(
        int $sideQuestResultID,
        int $moment,
        string $heroUuid,
        string $attackUuid,
        string $itemUuid,
        string $minionUuid)
    {
        $this->sideQuestResultID = $sideQuestResultID;
        $this->moment = $moment;
        $this->heroUuid = $heroUuid;
        $this->attackUuid = $attackUuid;
        $this->itemUuid = $itemUuid;
        $this->minionUuid = $minionUuid;
    }
}
