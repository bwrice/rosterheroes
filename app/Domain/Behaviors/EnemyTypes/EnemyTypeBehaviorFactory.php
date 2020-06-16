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
            case EnemyType::GOLEM:
                return app(GolemBehavior::class);
            case EnemyType::IMP:
                return app(ImpBehavior::class);
            case EnemyType::TROLL:
                return app(TrollBehavior::class);
            case EnemyType::UNDEAD:
                return app(UndeadBehavior::class);
            case EnemyType::VAMPIRE:
                return app(VampireBehavior::class);
            case EnemyType::WEREWOLF:
                return app(WerewolfBehavior::class);
            case EnemyType::WITCH:
                return app(WitchBehavior::class);
        }
        throw new UnknownBehaviorException($enemyTypeName, EnemyTypeBehavior::class);
    }
}
