<?php

namespace App;

use App\Events\SquadCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class EventSourcedModel
 * @package App
 *
 * @method static Builder uuid($uuid)
 */
abstract class EventSourcedModel extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @param $attributes
     * @throws \Exception
     *
     * @return static
     */
    public static function createWithAttributes(array $attributes = [])
    {
        $attributes['id'] = (string) Uuid::uuid4();

        new SquadCreated($attributes);

        return static::find($attributes['id']);
    }

    abstract protected static function triggerCreatedEvent(array $attributes);

    public function scopeUuid(Builder $query, $uuid)
    {
        return $query->where('uuid', '=', $uuid);
    }

//    /*
//     * A helper method to quickly retrieve an account by uuid.
//     */
//    public static function uuid(string $uuid): ?self
//    {
//        return static::where('uuid', $uuid)->first();
//    }
}
