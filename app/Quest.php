<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Quest
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $uuid
 *
 * @property Province $province
 */
class Quest extends EventSourcedModel
{
    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
