<?php

namespace App;

use App\Domain\Models\Player;
use Illuminate\Database\Eloquent\Model;

class ExternalPlayer extends Model
{
    protected $guarded = [];

    public function statsIntegrationType()
    {
        return $this->belongsTo(StatsIntegrationType::class, 'integration_type_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
