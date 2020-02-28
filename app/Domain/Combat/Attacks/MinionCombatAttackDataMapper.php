<?php


namespace App\Domain\Combat\Attacks;


class MinionCombatAttackDataMapper
{
    /**
     * @var CombatAttackDataMapper
     */
    protected $combatAttackDataMapper;

    public function __construct(CombatAttackDataMapper $combatAttackDataMapper)
    {
        $this->combatAttackDataMapper = $combatAttackDataMapper;
    }

    public function getMinionCombatAttack(array $data)
    {
        return new MinionCombatAttack(
            $data['minionUuid'],
            $this->combatAttackDataMapper->getCombatAttack($data['combatAttack'])
        );
    }
}
