<?php

namespace App;

use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\RewardsChests;
use App\Domain\Models\EventSourcedModel;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;

/**
 * Class Chest
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $gold
 * @property int $quality
 * @property int $size
 * @property CarbonInterface $opened_at
 * @property int $squad_id
 * @property int|null $chest_blueprint_id
 * @property string|null $source_type
 * @property string|null $source_id
 *
 * @property Squad $squad
 * @property RewardsChests $source
 * @property ChestBlueprint|null $chestBlueprint
 *
 * @property ItemCollection $items
 */
class Chest extends EventSourcedModel
{
    const RELATION_MORPH_MAP_KEY = 'chests';

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function chestBlueprint()
    {
        return $this->belongsTo(ChestBlueprint::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->morphMany(Item::class, 'has_items');
    }

    public function getDescription()
    {
        return $this->chestBlueprint->getChestDescription();
    }
}
