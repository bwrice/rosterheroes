<?php

namespace App\Domain\Models;

use App\Domain\Traits\HasUuid;
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
    use HasUuid;
}
