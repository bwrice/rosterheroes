<?php

namespace App\Domain\Models;

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
 */
class SideQuestSnapshot extends Model
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
}
