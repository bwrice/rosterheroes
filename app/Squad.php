<?php

namespace App;

use App\Events\HeroCreated;
use App\Events\SquadCreated;
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

    /**
     * @param int $userID
     * @param string $name
     * @param array $heroesData
     *
     * @return Squad
     *
     * Creates a new Squad with Heroes and triggers associated events
     */
    public static function generate(int $userID, string $name, array $heroesData)
    {
        /** @var Squad $squad */
        $squad = self::create([
            'user_id' => $userID,
            'name' => $name,
            'squad_rank_id' => SquadRank::getStarting()->id,
            'province_id' => Province::getStarting()->id,
        ]);

        event(new SquadCreated($squad));

        $heroClasses = HeroClass::all();
        $heroRaces = HeroRace::all();
        $startingRank = HeroRank::getStarting();

        foreach($heroesData as $hero) {
            /** @var Hero $hero */
            $hero = $squad->heroes()->create([
                'name' => $hero['name'],
                'hero_class_id' => $heroClasses->where('name', '=', $hero['class'])->first()->id,
                'hero_race_id' => $heroRaces->where('name', '=', $hero['race'])->first()->id,
                'hero_rank_id' => $startingRank->id
            ]);

            event(new HeroCreated($hero));
        }

        return $squad->load('heroes', 'wagon', 'province');
    }

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
