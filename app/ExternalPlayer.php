<?php

namespace App;

use App\Domain\Models\Player;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExternalPlayer
 * @package App
 *
 * @property string $external_id
 * @property int $int_type_id
 */
class ExternalPlayer extends Model
{
    protected $guarded = [];

    public function statsIntegrationType()
    {
        return $this->belongsTo(StatsIntegrationType::class, 'int_type_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
