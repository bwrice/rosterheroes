<?php

namespace App;

use App\Events\HeroCreated;
use App\Events\SquadFavorIncreased;
use App\Events\SquadCreated;
use App\Events\SquadCreationRequested;
use App\Events\SquadGoldIncreased;
use App\Events\SquadSalaryIncreased;
use App\Wagons\Wagon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class Squad
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $name
 * @property int $squad_rank_id
 * @property int $province_id
 * @property int $salary
 * @property int $experience
 * @property int $gold
 * @property int $favor
 *
 * @property Province $province
 * @property Wagon $wagon
 * @property Collection $heroes
 */
class Squad extends Model
{
    const STARTING_GOLD = 500;
    const STARTING_FAVOR = 100;
    const STARTING_SALARY = 30000;

    protected $guarded = [];

    /**
     * @param int $userID
     * @param string $name
     * @param array $heroesData
     * @return Squad
     * @throws \Exception
     *
     * Creates a new Squad with Heroes and triggers associated events
     */
    public static function generate(int $userID, string $name, array $heroesData)
    {
        /** @var Squad $squad */
        $squad = self::createWithAttributes([
            'user_id' => $userID,
            'name' => $name,
            'squad_rank_id' => SquadRank::getStarting()->id,
            'province_id' => Province::getStarting()->id
        ]);

        // Hooked into for adding wagon
        event(new SquadCreated($squad));

        // Give starting salary, gold and favor to new squad
        $squad->increaseSalary(self::STARTING_SALARY);
        $squad->increaseGold(self::STARTING_GOLD);
        $squad->increaseFavor(self::STARTING_FAVOR);

        foreach($heroesData as $hero) {

            Hero::generate($squad, $hero['name'], $hero['class'], $hero['race']);
        }

        return $squad->load('heroes', 'wagon', 'province');
    }

    public function increaseSalary(int $amount)
    {
        event(new SquadSalaryIncreased($this->uuid, $amount));
    }

    public function increaseGold(int $amount)
    {
        event(new SquadGoldIncreased($this->uuid, $amount));
    }

    public function increaseFavor(int $amount)
    {
        event(new SquadFavorIncreased($this->uuid, $amount));
    }

    /**
     * @param array $attributes
     * @return Squad|null
     * @throws \Exception
     */
    public static function createWithAttributes(array $attributes)
    {
        $uuid = (string) Uuid::uuid4();

        $attributes['uuid'] = $uuid;

        event(new SquadCreationRequested($attributes));

        return self::uuid($uuid);
    }

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function uuid(string $uuid): ?Squad
    {
        return static::where('uuid', $uuid)->first();
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

    public function stashes()
    {
        return $this->hasMany(Stash::class);
    }


    public function getLocalStash()
    {
        return $this->stashes()->firstOrCreate([
            'province_id' => $this->province_id
        ]);
    }

    public function storeHouses()
    {
        return $this->hasMany(StoreHouse::class);
    }

    /**
     * @return StoreHouse|null
     */
    public function getLocalStoreHouse(): ?StoreHouse
    {
        return $this->storeHouses()->where('province_id', '=', $this->province_id)->first();
    }
}
