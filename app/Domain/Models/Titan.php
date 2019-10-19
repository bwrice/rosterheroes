<?php

namespace App\Domain\Models;

use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

class Titan extends Model
{
    use HasNameSlug;

    protected $guarded = [];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class);
    }
}
