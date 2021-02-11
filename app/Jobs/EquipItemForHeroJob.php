<?php

namespace App\Jobs;

use App\Domain\Actions\EquipItemForHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class EquipItemForHeroJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Item $item;
    public Hero $hero;

    /**
     * EquipItemForHeroJob constructor.
     * @param Item $item
     * @param Hero $hero
     */
    public function __construct(Item $item, Hero $hero)
    {
        $this->item = $item;
        $this->hero = $hero;
    }

    /**
     * @param EquipItemForHeroAction $equipItemForHeroAction
     */
    public function handle(EquipItemForHeroAction $equipItemForHeroAction)
    {
        $equipItemForHeroAction->execute($this->item, $this->hero);
    }
}
