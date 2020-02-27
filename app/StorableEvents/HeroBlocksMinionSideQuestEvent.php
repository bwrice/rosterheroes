<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class HeroBlocksMinionSideQuestEvent extends MinionAttacksHeroSideQuestEvent implements ShouldBeStored
{

}
