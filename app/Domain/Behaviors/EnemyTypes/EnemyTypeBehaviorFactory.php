<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\EnemyType;
use App\Exceptions\UnknownBehaviorException;

class EnemyTypeBehaviorFactory
{
    public function getBehavior($enemyTypeName): EnemyTypeBehavior
    {
        switch ($enemyTypeName) {
            case EnemyType::SKELETON:
                return app(SkeletonBehavior::class);
        }
        throw new UnknownBehaviorException($enemyTypeName, EnemyTypeBehavior::class);
    }
}
