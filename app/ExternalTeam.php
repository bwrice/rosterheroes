<?php

namespace App;

use App\Domain\Models\Team;
use Illuminate\Database\Eloquent\Model;

class ExternalTeam extends Model
{
    protected $guarded = [];

    public function statsIntegration()
    {
        return $this->belongsTo(StatsIntegration::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
