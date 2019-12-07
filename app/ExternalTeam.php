<?php

namespace App;

use App\Domain\Models\Team;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExternalTeam
 * @package App
 *
 * @property string $external_id
 * @property int $integration_type_id
 *
 * @property Team $team
 * @property StatsIntegrationType $statsIntegrationType
 */
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
