<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class MinionDamagesHeroSideQuestEvent implements ShouldBeStored
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
    /**
     * @var int
     */
    public $damage;

    public function __construct(
        int $sideQuestResultID,
        int $moment,
        string $minionUuid,
        string $attackUuid,
        string $heroUuid,
        int $damage)
    {
        $this->sideQuestResultID = $sideQuestResultID;
        $this->moment = $moment;
        $this->minionUuid = $minionUuid;
        $this->attackUuid = $attackUuid;
        $this->heroUuid = $heroUuid;
        $this->damage = $damage;
    }
}
