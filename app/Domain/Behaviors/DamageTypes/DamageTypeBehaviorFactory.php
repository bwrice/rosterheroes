<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Models\DamageType;
use App\Exceptions\UnknownBehaviorException;

class DamageTypeBehaviorFactory
{
    public function getBehavior(string $damageTypeName): DamageTypeBehaviorInterface
    {
        switch($damageTypeName) {
            case DamageType::SINGLE_TARGET:
                return app(SingleTargetBehavior::class);
            case DamageType::MULTI_TARGET:
                return app(MultiTargetBehavior::class);
            case DamageType::DISPERSED:
                return app(DispersedBehavior::class);
            case DamageType::AREA_OF_EFFECT:
                return app(AreaOfEffectBehavior::class);
        }

        throw new UnknownBehaviorException($damageTypeName, DamageTypeBehaviorInterface::class);
    }
}
