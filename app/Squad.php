<?php

namespace App;

use App\Wagons\Wagon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Squad
 * @package App
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $squad_rank_id
 * @property int $province_id
 *
 * @property Province $province
 * @property Wagon $wagon
 * @property Collection $heroes
 */
class Squad extends Model
{
    protected $guarded = [];

    public function build(User $user, string $name, array $heroesData)
    {
        $this->user_id = $user->id;
        $this->name = $name;
        $this->squad_rank_id = SquadRank::getStarting()->id;
        $this->province_id = Province::getStarting()->id;
        $this->save();
        (new Wagon)->build($this);
        foreach($heroesData as $heroData) {
            (new Hero)->build($this, $heroData);
        }
        return $this->fresh();
    }

    public function wagon()
    {
        return $this->hasOne(Wagon::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function heroes()
    {
        return $this->hasMany(Hero::class);
    }
}
