<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class SpellAddedToLibrary implements ShouldBeStored
{
    /** @var int */
    public $spellID;

    public function __construct(int $spellID)
    {
        $this->spellID = $spellID;
    }
}
