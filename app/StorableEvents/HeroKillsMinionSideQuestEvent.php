<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class HeroKillsMinionSideQuestEvent extends HeroAttacksMinionSideQuestEvent implements ShouldBeStored
{

}
