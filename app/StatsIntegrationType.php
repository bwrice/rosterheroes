<?php

namespace App;

use App\Domain\Models\Traits\HasUniqueNames;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StatsIntegrationType
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class StatsIntegrationType extends Model
{
    use HasUniqueNames;

    protected $guarded = [];
}
