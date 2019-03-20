<?php

namespace App;

use App\Domain\Teams\Team;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sport
 * @package App
 *
 * @property int $id
 * @property int $sport_id
 * @property string $sport
 * @property string $abbreviation
 */
class League extends Model
{
    const NFL = 'NFL';
    const MLB = 'MLB';
    const NBA = 'NBA';
    const NHL = 'NHL';

    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
