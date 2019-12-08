<?php

namespace App;

use App\Domain\Models\Player;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExternalPlayer
 * @package App
 *
 * @property string $external_id
 * @property int $integration_type_id
 */
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
