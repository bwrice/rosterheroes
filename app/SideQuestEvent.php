<?php

namespace App;

use App\Domain\Models\EventSourcedModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestEvent
 * @package App
 *
 * @property array $data
 * @property string $uuid
 * @property string $event_type
 * @property int $moment
 *
 * @property SideQuestResult $sideQuestResult
 */
class SideQuestEvent extends EventSourcedModel
{
    public const TYPE_HERO_DAMAGES_MINION = 'hero-damages-minion';
    public const TYPE_HERO_KILLS_MINION = 'hero-kills-minion';
    public const TYPE_MINION_BLOCKS_HERO = 'minion-blocks-hero';

    protected $guarded = [];
    protected $casts = [
        'data' => 'array'
    ];

    public function sideQuestResult()
    {
        return $this->belongsTo(SideQuestResult::class);
    }
}
