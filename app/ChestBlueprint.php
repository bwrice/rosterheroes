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
 * @property int $quality
 * @property int $size
 * @property int $min_gold
 * @property int $max_gold
 * @property string $reference_id
 *
 * @property Collection $itemBlueprints
 * @property MinionCollection $minions
 */
class ChestBlueprint extends Model
{
    public const LOW_TIER_SMALL_WARRIOR_CHEST = 'A';
    public const LOW_TIER_SMALL_RANGER_CHEST = 'B';
    public const LOW_TIER_SMALL_SORCERER_CHEST = 'C';
    public const LOW_TIER_MEDIUM_WARRIOR_CHEST = 'D';
    public const LOW_TIER_MEDIUM_RANGER_CHEST = 'E';
    public const LOW_TIER_MEDIUM_SORCERER_CHEST = 'F';
    public const LOW_TIER_LARGE_WARRIOR_CHEST = 'G';
    public const LOW_TIER_LARGE_RANGER_CHEST = 'H';
    public const LOW_TIER_LARGE_SORCERER_CHEST = 'I';
    public const MID_TIER_SMALL_WARRIOR_CHEST = 'J';
    public const MID_TIER_SMALL_RANGER_CHEST = 'K';
    public const MID_TIER_SMALL_SORCERER_CHEST = 'L';
    public const MID_TIER_MEDIUM_WARRIOR_CHEST = 'M';
    public const MID_TIER_MEDIUM_RANGER_CHEST = 'N';
    public const MID_TIER_MEDIUM_SORCERER_CHEST = 'O';
    public const MID_TIER_LARGE_WARRIOR_CHEST = 'P';
    public const MID_TIER_LARGE_RANGER_CHEST = 'Q';
    public const MID_TIER_LARGE_SORCERER_CHEST = 'R';
    public const HIGH_TIER_SMALL_WARRIOR_CHEST = 'S';
    public const HIGH_TIER_SMALL_RANGER_CHEST = 'T';
    public const HIGH_TIER_SMALL_SORCERER_CHEST = 'U';
    public const HIGH_TIER_MEDIUM_WARRIOR_CHEST = 'V';
    public const HIGH_TIER_MEDIUM_RANGER_CHEST = 'W';
    public const HIGH_TIER_MEDIUM_SORCERER_CHEST = 'X';
    public const HIGH_TIER_LARGE_WARRIOR_CHEST = 'Y';
    public const HIGH_TIER_LARGE_RANGER_CHEST = 'Z';
    public const HIGH_TIER_LARGE_SORCERER_CHEST = 'AA';


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
