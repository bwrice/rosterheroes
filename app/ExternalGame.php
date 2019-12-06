<?php

namespace App;

use App\Domain\Models\Game;
use Illuminate\Database\Eloquent\Model;

class ExternalGame extends Model
{
    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function statsIntegrationType()
    {
        return $this->belongsTo(StatsIntegrationType::class, 'integration_type_id');
    }
}
