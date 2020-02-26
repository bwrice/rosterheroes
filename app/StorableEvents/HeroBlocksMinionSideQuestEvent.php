<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class HeroBlocksMinionSideQuestEvent implements ShouldBeStored
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
    public $minionUuid;
    /**
     * @var string
     */
    public $attackUuid;
    /**
     * @var string
     */
    public $heroUuid;

    public function __construct(
        int $sideQuestResultID,
        int $moment,
        string $minionUuid,
        string $attackUuid,
        string $heroUuid)
    {
        $this->sideQuestResultID = $sideQuestResultID;
        $this->moment = $moment;
        $this->minionUuid = $minionUuid;
        $this->attackUuid = $attackUuid;
        $this->heroUuid = $heroUuid;
    }
}
