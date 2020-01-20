<?php

namespace App\Domain\Models;

use App\Domain\Models\Game;
use App\Domain\Models\StatsIntegrationType;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExternalGame
 * @package App
 *
 * @property string $external_id
 * @property int $integration_type_id
 *
 * @property StatsIntegrationType $statsIntegrationType
 * @property Game $game
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
