<?php


namespace App\Domain\Traits;


use App\Domain\Models\EventSourcedModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasUuid
 * @package App\Domain\Traits
 *
 * @method static Builder query
 */
trait HasUuid
{
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
