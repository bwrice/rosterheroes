<?php

namespace App\Domain\Models;

use App\Domain\Collections\HeroCollection;
use App\Domain\Collections\PlayerSpiritCollection;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;

/**
 * Class PlayerWeek
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $essence_cost
 * @property int $energy
 * @property int $game_id
 * @property int $player_id
 * @property int $player_game_log_id
 * @property int $week_id
 *
 * @property Week $week
 * @property PlayerGameLog $playerGameLog
 * @property HeroCollection $heroes
 *
 * @method static PlayerSpiritQueryBuilder query()
 * @method static PlayerSpiritQueryBuilder withPosition(string $position)
 */
class PlayerSpirit extends EventSourcedModel
{
    public const ESSENCE_COST_PER_POINT = 400;
    public const STARTING_ENERGY = 100;
    public const MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT = 10;
    public const MIN_MAX_ENERGY_RATIO = 4;

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

    public function heroes()
    {
        return $this->hasMany(Hero::class);
    }

    public function getPositions()
    {
        return $this->playerGameLog->player->positions;
    }

    public function scopeWithPositions(PlayerSpiritQueryBuilder $builder, array $positions)
    {
        return $builder->withPositions($positions);
    }

    public function scopeWithPosition(PlayerSpiritQueryBuilder $builder, string $position)
    {
        return $builder->withPositions((array) $position);
    }
}
