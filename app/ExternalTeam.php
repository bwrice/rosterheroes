<?php

namespace App;

use App\Domain\Models\Team;
use Illuminate\Database\Eloquent\Model;

class ExternalTeam extends Model
{
    protected $guarded = [];

    public function statsIntegrationType()
    {
        return $this->belongsTo(StatsIntegrationType::class, 'integration_type_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
