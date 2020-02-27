<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class MinionKillsHeroSideQuestEvent extends MinionAttacksHeroSideQuestEvent implements ShouldBeStored
{

}
