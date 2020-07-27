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
        return $this->grade/400;
    }

    public function getBaseDamageModifierBonus()
    {
        return $this->grade/50;
    }

    public function getDamageMultiplierModifierBonus()
    {
        return $this->grade/200;
    }

    public function getWeightModifier()
    {
        $weightModifier = 1 + $this->grade/100;
        $weightModifier *=  $this->materialType->getWeightModifier();
        return $weightModifier;
    }

    public function getProtectionModifier()
    {
        $protectionModifier = 1 + $this->grade/200;
        $protectionModifier *= $this->materialType->getProtectionModifier();
        return $protectionModifier;
    }

    public function getBlockChanceModifier()
    {
        $blockChanceModifier = 1 + $this->grade/300;
        return $blockChanceModifier;
    }

    public function getValueModifier()
    {
        $valueModifier = 1 + $this->grade/100;
        return $valueModifier;
    }
}
