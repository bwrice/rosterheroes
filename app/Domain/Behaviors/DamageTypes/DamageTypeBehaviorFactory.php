<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Models\DamageType;
use App\Exceptions\UnknownBehaviorException;

class DamageTypeBehaviorFactory
{
    public function getBehavior(string $damageTypeName): DamageTypeBehavior
    {
        switch($damageTypeName) {
            case DamageType::FIXED_TARGET:
                return app(FixedTargetBehavior::class);
            case DamageType::DISPERSED:
                return app(DispersedBehavior::class);
            case DamageType::AREA_OF_EFFECT:
                return app(AreaOfEffectBehavior::class);
        }

        throw new UnknownBehaviorException($damageTypeName, DamageTypeBehavior::class);
    }
}
