<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Material
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property int $grade
 *
 * @property MaterialType $materialType
 */
class Material extends Model
{
    protected $guarded = [];

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class);
    }

    public function getWeightModifier()
    {
        $gradeModifier = 1 + ($this->grade**.25/5);
        $materialTypeModifier =  $this->materialType->getWeightModifier();
        return $gradeModifier * $materialTypeModifier;
    }

    public function getProtectionModifier()
    {
        $protectionModifier = 1 + ($this->grade**.25/5);
        return $protectionModifier;
    }
}
