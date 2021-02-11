<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use Illuminate\Support\Collection;

class FindItemsToEquip
{
    protected Collection $equipArrays;
    protected FindItemsForHeroToEquip $findItemsForHeroToEquip;

    public function __construct(FindItemsForHeroToEquip $findItemsForHeroToEquip)
    {
        $this->findItemsForHeroToEquip = $findItemsForHeroToEquip;
    }

    public function execute(Squad $npc)
    {
        $this->equipArrays = collect();
        $npc->heroes->each(function (Hero $hero) {
            $itemsToEquip = $this->findItemsForHeroToEquip->execute($hero);
            $this->equipArrays->push([
                'hero' => $hero,
                'items' => $itemsToEquip
            ]);
        });
        return $this->equipArrays;
    }
}
