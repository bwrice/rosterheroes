<?php

namespace App;

use App\Domain\Collections\ItemCollection;
use App\Domain\Collections\SpellCollection;
use App\Domain\Interfaces\HasAttackSnapshots;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Spell;
use App\Domain\Models\SquadSnapshot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
 * @property ItemCollection $items
 * @property Collection $attackSnapshots
 * @property SpellCollection $spells
 */
class HeroSnapshot extends Model implements HasAttackSnapshots
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

    public function items()
    {
        return $this->belongsToMany(Item::class)->withTimestamps();
    }

    public function spells()
    {
        return $this->belongsToMany(Spell::class)->withTimestamps();
    }

    public function attackSnapshots(): MorphMany
    {
        return $this->morphMany(AttackSnapshot::class, 'attacker');
    }
}
