<?php

namespace App;

use App\Domain\Collections\MinionCollection;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChestBlueprint
 * @package App
 *
 * @property int $id
 * @property int $quality_tier
 * @property int $size_tier
 * @property int $min_gold
 * @property int $max_gold
 * @property string $reference_id
 *
 * @property Collection $itemBlueprints
 * @property MinionCollection $minions
 */
class ChestBlueprint extends Model
{
    public const LOW_TIER_WARRIOR_CHEST = 'A';
    public const LOW_TIER_RANGER_CHEST = 'B';
    public const LOW_TIER_SORCERER_CHEST = 'C';
    public const LOW_TIER_MEDIUM_WARRIOR_CHEST = 'D';
    public const LOW_TIER_MEDIUM_RANGER_CHEST = 'E';
    public const LOW_TIER_MEDIUM_SORCERER_CHEST = 'F';
    public const LOW_TIER_LARGE_WARRIOR_CHEST = 'D';
    public const LOW_TIER_LARGE_RANGER_CHEST = 'E';
    public const LOW_TIER_LARGE_SORCERER_CHEST = 'F';
    public const MID_TIER_WARRIOR_CHEST = 'A';
    public const MID_TIER_RANGER_CHEST = 'B';
    public const MID_TIER_SORCERER_CHEST = 'C';
    public const MID_TIER_LARGE_WARRIOR_CHEST = 'D';
    public const MID_TIER_LARGE_RANGER_CHEST = 'E';
    public const MID_TIER_LARGE_SORCERER_CHEST = 'F';
    public const HIGH_TIER_WARRIOR_CHEST = 'A';
    public const HIGH_TIER_RANGER_CHEST = 'B';
    public const HIGH_TIER_SORCERER_CHEST = 'C';
    public const HIGH_TIER_LARGE_WARRIOR_CHEST = 'D';
    public const HIGH_TIER_LARGE_RANGER_CHEST = 'E';
    public const HIGH_TIER_LARGE_SORCERER_CHEST = 'F';


    protected $guarded = [];

    public function itemBlueprints()
    {
        return $this->belongsToMany(ItemBlueprint::class)->withPivot(['chance', 'count'])->withTimestamps();
    }

    public function chests()
    {
        return $this->hasMany(Chest::class);
    }

    public function minions()
    {
        return $this->belongsToMany(Minion::class)->withTimestamps();
    }

    public function sideQuests()
    {
        return $this->belongsToMany(SideQuest::class)->withTimestamps();
    }
}
