<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Squad;
use Illuminate\Support\Collection;

class FindChestsToOpen
{
    public const CAPACITY_PER_CHEST = 50;

    /**
     * @param Squad $npc
     * @return Collection
     */
    public function execute(Squad $npc)
    {
        $unopenedChests = $npc->chests()
            ->where('opened_at', '=', null)
            ->get();

        if ($unopenedChests->isEmpty()) {
            return collect();
        }

        $capacityCount = (int) ceil($npc->getAvailableCapacity()/self::CAPACITY_PER_CHEST);
        $chestsCount = (int) min($unopenedChests->count(), $capacityCount);

        return $unopenedChests->random($chestsCount);
    }
}
