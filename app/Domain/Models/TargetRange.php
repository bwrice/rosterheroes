<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TargetRange
 * @package App\Domain\Models
 *
 * @property string $name
 */
class TargetRange extends Model
{
    public const MELEE = 'melee';
    public const MID_RANGE = 'mid-range';
    public const LONG_RANGE = 'long-range';

    protected $guarded = [];
}
