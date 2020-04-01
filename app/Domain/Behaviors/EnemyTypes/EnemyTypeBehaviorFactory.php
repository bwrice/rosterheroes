<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\EnemyType;
use App\Exceptions\UnknownBehaviorException;

class EnemyTypeBehaviorFactory
{
    public function getBehavior($enemyTypeName): EnemyTypeBehavior
    {
        switch ($enemyTypeName) {
            case EnemyType::GARGOYLE:
                return app(GargoyleBehavior::class);
            case EnemyType::GIANT:
                return app(GiantBehavior::class);
            case EnemyType::SKELETON:
                return app(SkeletonBehavior::class);
            case EnemyType::VAMPIRE:
                return app(VampireBehavior::class);
        }
        throw new UnknownBehaviorException($enemyTypeName, EnemyTypeBehavior::class);
    }
}
