<?php

namespace App\Providers;

use App\Chest;
use App\Domain\Models\Enchantment;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Residence;
use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
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
        // TODO: add any other model with potential polymorphic relationships
        Relation::morphMap([
            Squad::RELATION_MORPH_MAP_KEY => Squad::class,
            Hero::RELATION_MORPH_MAP_KEY => Hero::class,
            Spell::RELATION_MORPH_MAP_KEY => Spell::class,
            Enchantment::RELATION_MORPH_MAP_KEY => Enchantment::class,
            Item::RELATION_MORPH_MAP_KEY => Item::class,
            Stash::RELATION_MORPH_MAP_KEY => Stash::class,
            Residence::RELATION_MORPH_MAP_KEY => Residence::class,
            Chest::RELATION_MORPH_MAP_KEY => Chest::class
        ]);
    }
}
