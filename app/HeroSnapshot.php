<?php

namespace App;

use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\SquadSnapshot;
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
 * @property int $health
 * @property int $stamina
 * @property int $mana
 * @property int $protection
 * @property float $block_chance
 *
 * @property Hero $hero
 * @property SquadSnapshot $squadSnapshot
 * @property PlayerSpirit|null $playerSpirit
 * @property CombatPosition $combatPosition
 */
class HeroSnapshot extends Model
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
}
