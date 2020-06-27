<?php

namespace App\Domain\Models;

use App\Domain\Collections\MinionCollection;
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
 * @property string|null $description
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
    public const GOLD_ONLY_LEVEL_1 = 'AB';
    public const GOLD_ONLY_LEVEL_2 = 'AC';
    public const GOLD_ONLY_LEVEL_3 = 'AD';
    public const GOLD_ONLY_LEVEL_4 = 'AE';
    public const GOLD_ONLY_LEVEL_5 = 'AF';
    public const GOLD_ONLY_LEVEL_6 = 'AG';
    public const GOLD_ONLY_LEVEL_7 = 'AH';
    public const GOLD_ONLY_LEVEL_8 = 'AI';
    public const GOLD_ONLY_LEVEL_9 = 'AJ';
    public const GOLD_ONLY_LEVEL_10 = 'AK';
    public const GOLD_ONLY_LEVEL_11 = 'AL';
    public const GOLD_ONLY_LEVEL_12 = 'AM';
    public const FULLY_RANDOM_TINY = 'AN';
    public const FULLY_RANDOM_SMALL = 'AO';
    public const FULLY_RANDOM_MEDIUM = 'AP';
    public const FULLY_RANDOM_LARGE = 'AQ';
    public const FULLY_RANDOM_VERY_LARGE = 'AR';
    public const FULLY_RANDOM_GIGANTIC = 'AS';
    public const TINY_LOW_TIER_RANDOM = 'AT';
    public const SMALL_LOW_TIER_RANDOM = 'AU';
    public const MEDIUM_LOW_TIER_RANDOM = 'AV';
    public const LARGE_LOW_TIER_RANDOM = 'AX';
    public const VERY_LARGE_LOW_TIER_RANDOM = 'AY';
    public const GIGANTIC_LOW_TIER_RANDOM = 'AZ';
    public const TINY_MID_TIER_RANDOM = 'BA';
    public const SMALL_MID_TIER_RANDOM = 'BB';
    public const MEDIUM_MID_TIER_RANDOM = 'BC';
    public const LARGE_MID_TIER_RANDOM = 'BD';
    public const VERY_LARGE_MID_TIER_RANDOM = 'BE';
    public const GIGANTIC_MID_TIER_RANDOM = 'BF';
    public const TINY_HIGH_TIER_RANDOM = 'BH';
    public const SMALL_HIGH_TIER_RANDOM = 'BI';
    public const MEDIUM_HIGH_TIER_RANDOM = 'BJ';
    public const LARGE_HIGH_TIER_RANDOM = 'BK';
    public const VERY_LARGE_HIGH_TIER_RANDOM = 'BL';
    public const GIGANTIC_HIGH_TIER_RANDOM = 'BM';
    public const NEWCOMER_CHEST = 'BN';


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
        return $this->belongsToMany(Minion::class)->withPivot(['chance', 'count'])->withTimestamps();
    }

    public function sideQuests()
    {
        return $this->belongsToMany(SideQuest::class)->withPivot(['chance', 'count'])->withTimestamps();
    }
}
