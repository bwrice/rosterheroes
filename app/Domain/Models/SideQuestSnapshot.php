<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $week_id
 * @property int $side_quest_id
 * @property string|null $name
 * @property int $difficulty
 * @property int $experience_reward
 * @property int $favor_reward
 * @property float $experience_per_moment
 *
 * @property Week $week
 * @property SideQuest $sideQuest
 *
 * @property Collection $minionSnapshots
 */
class SideQuestSnapshot extends BaseSideQuest
{
    protected $guarded = [];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function sideQuest()
    {
        return $this->belongsTo(SideQuest::class);
    }

    public function minionSnapshots()
    {
        return $this->belongsToMany(MinionSnapshot::class, 'm_snapshot_sq_snapshot')->withPivot(['count'])->withTimestamps();
    }

    protected function getBaseMinions(): \Illuminate\Support\Collection
    {
        return $this->minionSnapshots;
    }
}
