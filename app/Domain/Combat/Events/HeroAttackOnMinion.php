<?php


namespace App\Domain\Combat\Events;


use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\Combatants\CombatMinion;

class HeroAttackOnMinion
{
    /**
     * @var HeroCombatAttack
     */
    protected $heroCombatAttack;
    /**
     * @var CombatMinion
     */
    protected $combatMinion;
    /**
     * @var int
     */
    protected $damageReceived;
    protected $block;
    protected $kill;

    public function __construct(HeroCombatAttack $heroCombatAttack, CombatMinion $combatMinion, int $damageReceived, $block, $kill)
    {
        $this->heroCombatAttack = $heroCombatAttack;
        $this->combatMinion = $combatMinion;
        $this->damageReceived = $damageReceived;
        $this->block = $block;
        $this->kill = $kill;
    }

    public static function build(HeroCombatAttack $heroCombatAttack, CombatMinion $combatMinion, int $damageReceived, $block)
    {
        if ($block) {
            $damageReceived = 0;
            $kill = false;
        } else {
            $kill = $combatMinion->getCurrentHealth() <= 0;
        }

        return new self(
            clone $heroCombatAttack,
            clone $combatMinion,
            $damageReceived,
            $block,
            $kill
        );
    }

    /**
     * @return HeroCombatAttack
     */
    public function getHeroCombatAttack(): HeroCombatAttack
    {
        return $this->heroCombatAttack;
    }

    /**
     * @return CombatMinion
     */
    public function getCombatMinion(): CombatMinion
    {
        return $this->combatMinion;
    }

    /**
     * @return int
     */
    public function getDamageReceived(): int
    {
        return $this->damageReceived;
    }

    /**
     * @return mixed
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @return mixed
     */
    public function getKill()
    {
        return $this->kill;
    }
}
