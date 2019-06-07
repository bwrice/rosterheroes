<?php

namespace App\Domain\Models;

use App\Domain\Models\EventSourcedModel;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Events\WeeklyGamePlayerCreationRequested;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class PlayerWeek
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $initial_salary
 * @property int $salary
 *
 * @property Week $week
 * @property Player $player
 * @property Game $game
 * @property PlayerGameLog $playerGameLog
 */
class WeeklyGamePlayer extends EventSourcedModel
{
    public const MIN_SALARY = 3000;
    public const SALARY_PER_POINT = 400;
    public const STARTING_EFFECTIVENESS = 10000;

    /**
     * @param array $attributes
     * @return self|null
     * @throws \Exception
     */
    public static function createWithAttributes(array $attributes)
    {
        $uuid = (string) Uuid::uuid4();

        $attributes['uuid'] = $uuid;

        event(new WeeklyGamePlayerCreationRequested($attributes));

        return self::uuid($uuid);
    }

    public static function uuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
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
}
