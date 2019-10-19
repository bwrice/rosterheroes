<?php

namespace App\Domain\Models;

use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Minion
 * @package App\Domain\Models
 *
 * @property string $name
 */
class Minion extends Model
{
    use HasNameSlug;

    protected $guarded = [];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class)->withTimestamps();
    }
}
