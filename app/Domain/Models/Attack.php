<?php

namespace App\Domain\Models;

use App\Domain\Collections\ItemCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Attack
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 *
 * @property ItemCollection $items
 */
class Attack extends Model
{
    public function items()
    {
        return $this->belongsToMany(Item::class)->withTimestamps();
    }
}
