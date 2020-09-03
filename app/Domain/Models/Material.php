<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Material
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $uuid
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

    public function getSpeedModifierBonus()
    {
        return log($this->grade, 100)/10;
    }

    public function getBaseDamageModifierBonus()
    {
        return log($this->grade, 100)/4;
    }

    public function getDamageMultiplierModifierBonus()
    {
        return log($this->grade, 100)/4;
    }

    public function getWeightModifier()
    {
        $weightModifier = 1 + ($this->grade**1.2)/200;
        $weightModifier *=  $this->materialType->getWeightModifier();
        return $weightModifier;
    }

    public function getProtectionModifier()
    {
        $protectionModifier = 1 + ($this->grade**1.12)/200;
        $protectionModifier *= $this->materialType->getProtectionModifier();
        return $protectionModifier;
    }

    public function getBlockChanceModifier()
    {
        $blockChanceModifier = 1 + ($this->grade**1.1)/250;
        return $blockChanceModifier;
    }

    public function getValueModifier()
    {
        $valueModifier = 1 + ($this->grade**1.2)/100;
        return $valueModifier;
    }
}
