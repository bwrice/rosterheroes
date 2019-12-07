<?php

namespace App;

use App\Domain\Models\Game;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExternalGame
 * @package App
 *
 * @property string $external_id
 * @property int $integration_type_id
 */
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
