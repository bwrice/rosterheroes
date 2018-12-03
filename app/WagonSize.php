<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WagonSize extends Model
{
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const LARGE = 'large';

    protected $guarded = [];
}
