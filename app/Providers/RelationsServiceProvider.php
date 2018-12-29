<?php

namespace App\Providers;

use App\Enchantment;
use App\Hero;
use App\Item;
use App\ItemBlueprint;
use App\Spell;
use App\Wagons\Wagon;
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
            Wagon::RELATION_MORPH_MAP_KEY => Wagon::class,
            Hero::RELATION_MORPH_MAP_KEY => Hero::class,
            Spell::RELATION_MORPH_MAP_KEY => Spell::class,
            Enchantment::RELATION_MORPH_MAP_KEY => Enchantment::class,
            Item::RELATION_MORPH_MAP => Item::class
        ]);
    }
}
