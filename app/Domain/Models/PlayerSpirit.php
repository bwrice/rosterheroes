<?php

namespace App\Domain\Models;

use App\Domain\Collections\PlayerSpiritCollection;
use App\Domain\Models\EventSourcedModel;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;
use App\Events\PlayerSpiritCreationRequested;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class PlayerWeek
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $salary
 * @property int $energy
 *
 * @property Week $week
 * @property Player $player
 * @property Game $game
 * @property PlayerGameLog $playerGameLog
 *
 * @method static PlayerSpiritQueryBuilder query()
 * @method static PlayerSpiritQueryBuilder withPosition(string $position)
 */
class PlayerSpirit extends EventSourcedModel
{
    public const MIN_SALARY = 3000;
    public const SALARY_PER_POINT = 400;
    public const STARTING_ENERGY = 10000;

    /**
     * @param array $attributes
     * @return self|null
     * @throws \Exception
     */
    public static function createWithAttributes(array $attributes)
    {
        $uuid = (string) Uuid::uuid4();

        $attributes['uuid'] = $uuid;

        event(new PlayerSpiritCreationRequested($attributes));

        return self::uuid($uuid);
    }

    public static function uuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
    }

    public function newCollection(array $models = [])
    {
        return new PlayerSpiritCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new PlayerSpiritQueryBuilder($query);
    }

    public function playerGameLog()
    {
        return $this->belongsTo(PlayerGameLog::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getPositions()
    {
        return $this->player->positions;
    }

    public function scopeWithPosition(PlayerSpiritQueryBuilder $builder, string $position)
    {
        return $builder->withPositions($position);
    }
}
