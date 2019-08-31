<?php

namespace App\Domain\Models;

use App\Domain\Models\Traits\HasUniqueNames;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DamageType
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 */
class DamageType extends Model
{
    use HasUniqueNames;

    public const SINGLE_TARGET = 'single-target';
    public const MULTI_TARGET = 'multi-target';
    public const AREA_OF_EFFECT = 'area-of-effect';
    public const DISPERSED = 'dispersed';

    protected $guarded = [];

}
