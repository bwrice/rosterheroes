<?php

namespace App\StorableEvents;

use App\Domain\Models\Minion;
use Illuminate\Queue\SerializesModels;
use Spatie\EventSourcing\ShouldBeStored;

final class ItemDamagesMinion implements ShouldBeStored
{
    use SerializesModels;

    /**
     * @var int
     */
    public $damage;
    /**
     * @var Minion
     */
    public $minion;

    public function __construct(int $damage, Minion $minion)
    {
        $this->damage = $damage;
        $this->minion = $minion;
    }
}
