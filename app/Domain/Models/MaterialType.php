<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MaterialType
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property int $grade
 *
 * @property MaterialGroup $materialGroup
 */
class MaterialType extends Model
{
    protected $guarded = [];

    public function materialGroup()
    {
        return $this->belongsTo(MaterialGroup::class);
    }
}
