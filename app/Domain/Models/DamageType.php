<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class DamageType extends Model
{
    public const SINGLE_TARGET = 'single-target';
    public const MULTI_TARGET = 'multi-target';
    public const AREA_OF_EFFECT = 'area-of-effect';
    public const DISPERSED = 'dispersed';
}
