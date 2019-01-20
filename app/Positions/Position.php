<?php

namespace App\Positions;

use App\Sport;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Position
 * @package App
 *
 * @property string $name
 * @property int $sport_id
 *
 * @property Sport $sport
 */
class Position extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new PositionCollection($models);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
