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

    public function getBurdenModifier()
    {
        $weightModifier = 1 + ($this->grade**.25/5);
        $weightModifier *=  $this->materialType->getWeightModifier();
        return $weightModifier;
    }

    public function getProtectionModifier()
    {
        $protectionModifier = 1 + ($this->grade**.25/5);
        $protectionModifier *= $this->materialType->getProtectionModifier();
        return $protectionModifier;
    }

    public function getBlockChanceModifier()
    {
        $blockChanceModifier = 1 + ($this->grade**.25)/5;
        return $blockChanceModifier;
    }

    public function getValueModifier()
    {
        $valueModifier = 1 + ($this->grade**.5/2);
        return $valueModifier;
    }
}
