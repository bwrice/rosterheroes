<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasurableGroup extends Model
{
    const ATTRIBUTE = 'attribute';
    const RESOURCE = 'resource';
    const QUALITY = 'quality';

    protected $guarded = [];
}
