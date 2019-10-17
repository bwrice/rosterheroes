<?php


namespace App\Domain\Behaviors\TargetRanges;


use App\Domain\Models\CombatPosition;
use App\Exceptions\UnknownBehaviorException;

class CombatPositionBehaviorFactory
{
    public function getBehavior(string $combatPositionName): CombatPositionBehavior
    {
        switch ($combatPositionName) {
            case CombatPosition::FRONT_LINE:
                return app(FrontLineBehavior::class);
            case CombatPosition::BACK_LINE:
                return app(BackLineBehavior::class);
            case CombatPosition::HIGH_GROUND:
                return app(HighGroundBehavior::class);
        }

        throw new UnknownBehaviorException($combatPositionName, CombatPositionBehavior::class);
    }
}
