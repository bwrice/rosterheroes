<?php

namespace App\Domain\Models;

use App\Domain\Collections\ItemCollection;
use App\Domain\Collections\SpellCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\MeasurableSnapshot;
use App\Facades\MeasurableTypeFacade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $squad_snapshot_id
 * @property int $hero_id
 * @property int|null $player_spirit_id
 * @property int $combat_position_id
 * @property int $protection
 * @property float $block_chance
 * @property float $fantasy_power
 *
 * @property Hero $hero
 * @property SquadSnapshot $squadSnapshot
 * @property PlayerSpirit|null $playerSpirit
 * @property CombatPosition $combatPosition
 *
 * @property Collection $measurableSnapshots
 * @property Collection $itemSnapshots
 * @property SpellCollection $spells
 */
class HeroSnapshot extends Model implements UsesItems
{
    protected $guarded = [];

    public function squadSnapshot()
    {
        return $this->belongsTo(SquadSnapshot::class);
    }

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }

    public function measurableSnapshots()
    {
        return $this->hasMany(MeasurableSnapshot::class);
    }

    public function spells()
    {
        return $this->belongsToMany(Spell::class)->withTimestamps();
    }

    public function itemSnapshots()
    {
        return $this->hasMany(ItemSnapshot::class);
    }

    /**
     * @param string $measurableTypeName
     * @return MeasurableSnapshot
     */
    public function getMeasurableSnapshot(string $measurableTypeName)
    {
        return $this->measurableSnapshots->load('measurable')->first(function (MeasurableSnapshot $measurableSnapshot) use ($measurableTypeName) {
            return $measurableSnapshot->measurable->measurable_type_id === MeasurableTypeFacade::id($measurableTypeName);
        });
    }

    public function getBuffedMeasurableAmount(string $measurableTypeName): int
    {
        return $this->getMeasurableSnapshot($measurableTypeName)->buffed_amount;
    }
}
