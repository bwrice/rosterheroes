<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TargetPriority
 * @package App\Domain\Models
 *
 * @property string $name
 */
class TargetPriority extends Model
{
    public const ANY = 'any';

    protected $guarded = [];
}
