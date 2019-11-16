<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignStop
 * @package App\Domain\Models
 * 
 * @property int $id
 * @property string $uuid
 *
 * @property Campaign $campaign
 * @property Quest $quest
 */
class CampaignStop extends Model
{
    protected $guarded = [];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }
}
