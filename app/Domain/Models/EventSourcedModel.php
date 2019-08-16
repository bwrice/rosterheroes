<?php

namespace App\Domain\Models;

use App\StorableEvents\SquadCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class EventSourcedModel
 * @package App
 *
 * @property string $uuid
 */
abstract class EventSourcedModel extends Model
{
    protected  $guarded = [];

    /**
     * @param string $uuid
     * @return Builder
     */
    public static function uuid(string $uuid)
    {
        return static::query()->where('uuid', '=', $uuid);
    }

    /**
     *
     * A helper method to quickly retrieve by uuid.
     *
     * @param string $uuid
     * @return static|null
     */
    public static function findUuid(string $uuid)
    {
        /** @var static $model */
        $model = static::uuid($uuid)->first();
        return $model;
    }

    /**
     * @param string $uuid
     * @return static
     */
    public static function findUuidOrFail(string $uuid)
    {
        /** @var static $model */
        $model = static::uuid($uuid)->firstOrFail();
        return $model;
    }
}
