<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class EnchantmentAttachedToItem implements ShouldBeStored
{
    /**
     * @var int
     */
    public $enchantmentID;

    public function __construct(int $enchantmentID)
    {
        $this->enchantmentID = $enchantmentID;
    }
}
