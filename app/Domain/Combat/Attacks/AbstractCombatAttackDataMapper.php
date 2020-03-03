<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractCombatAttackDataMapper
{


    protected function getName(array $data)
    {
        return $data['name'];
    }

    protected function getAttackUuid(array $data)
    {
        return $data['attackUuid'];
    }

    protected function getDamage(array $data)
    {
        return $data['damage'];
    }

    protected function getCombatSpeed(array $data)
    {
        return $data['combatSpeed'];
    }

    protected function getGrade(array $data)
    {
        return $data['grade'];
    }

    protected function getMaxTargetsCount(array $data)
    {
        return $data['maxTargetsCount'];
    }

    protected function getAttackerPosition(array $data, Collection $allCombatPositions)
    {
        return $allCombatPositions->find($data['attackerPositionID']);
    }

    protected function getTargetPosition(array $data, Collection $allCombatPositions)
    {
        return $allCombatPositions->find($data['targetPositionID']);
    }

    protected function getTargetPriority(array $data, Collection $allTargetPriorities)
    {
        return $allTargetPriorities->find($data['targetPriorityID']);
    }

    protected function getDamageTypes(array $data, Collection $allDamageTypes)
    {
        return $allDamageTypes->find($data['damageTypeID']);
    }


}
