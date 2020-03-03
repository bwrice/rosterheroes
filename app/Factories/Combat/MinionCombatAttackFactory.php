<?php


namespace App\Factories\Combat;

use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Factories\Models\MinionFactory;
use Illuminate\Support\Str;

class MinionCombatAttackFactory extends AbstractCombatAttackFactory
{
    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $minionUuid = MinionFactory::new()->create()->uuid;
        $name = 'Test_Minion_Combat_Attack ' . rand(1, 99999);
        return new MinionCombatAttack(
            $minionUuid,
            $name,
            $this->getAttackUuid(),
            $this->getDamage(),
            $this->getCombatSpeed(),
            $this->getGrade(),
            $this->getMaxTargetsCount(),
            $this->getAttackerPosition(),
            $this->getTargetPosition(),
            $this->getTargetPriority(),
            $this->getDamageType(),
        );
    }
}
