<?php

namespace App\Providers;

use App\Domain\Models\Chest;
use App\Domain\Models\Enchantment;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemSnapshot;
use App\Domain\Models\Minion;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\Residence;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Domain\Models\Shop;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class RelationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            Squad::RELATION_MORPH_MAP_KEY => Squad::class,
            Hero::RELATION_MORPH_MAP_KEY => Hero::class,
            Spell::RELATION_MORPH_MAP_KEY => Spell::class,
            Enchantment::RELATION_MORPH_MAP_KEY => Enchantment::class,
            Item::RELATION_MORPH_MAP_KEY => Item::class,
            Stash::RELATION_MORPH_MAP_KEY => Stash::class,
            Residence::RELATION_MORPH_MAP_KEY => Residence::class,
            Chest::RELATION_MORPH_MAP_KEY => Chest::class,
            Minion::RELATION_MORPH_MAP_KEY => Minion::class,
            SideQuest::RELATION_MORPH_MAP_KEY => SideQuest::class,
            Shop::RELATION_MORPH_MAP_KEY => Shop::class,
            ItemSnapshot::RELATION_MORPH_MAP_KEY => ItemSnapshot::class,
            MinionSnapshot::RELATION_MORPH_MAP_KEY => MinionSnapshot::class,
        ]);
    }
}
