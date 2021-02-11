<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use Illuminate\Support\Collection;

class FindItemsToEquip
{
    protected Collection $equipArrays;
    protected Collection $exclude;
    protected FindItemsForHeroToEquip $findItemsForHeroToEquip;

    public function __construct(FindItemsForHeroToEquip $findItemsForHeroToEquip)
    {
        $this->findItemsForHeroToEquip = $findItemsForHeroToEquip;
    }

    public function execute(Squad $npc)
    {
        $this->equipArrays = collect();
        $this->exclude = collect();
        $npc->heroes->each(function (Hero $hero) {
            $itemsToEquip = $this->findItemsForHeroToEquip->execute($hero, $this->exclude);
            $this->equipArrays->push([
                'hero' => $hero,
                'items' => $itemsToEquip
            ]);
            $this->exclude = $this->exclude->merge($itemsToEquip);
        });
        return $this->equipArrays;
    }
}
