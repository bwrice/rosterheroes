<?php


namespace App\Domain\Combat\Events;


use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\Combatants\CombatMinion;

class HeroAttackOnMinion implements CombatEvent
{
    /**
     * @var string
     */
    protected $type;
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

    public function __construct(
        string $type,
        HeroCombatAttack $heroCombatAttack,
        CombatMinion $combatMinion,
        int $damageReceived,
        $block,
        $kill)
    {
        $this->type = $type;
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
            $type = 'minion-blocks-hero';
        } else {
            if ($combatMinion->getCurrentHealth() <= 0) {
                $kill = true;
                $type = 'hero-kills-minion';
            } else {
                $kill = false;
                $type = 'hero-damages-minion';
            }
        }

        return new self(
            $type,
            clone $heroCombatAttack,
            clone $combatMinion,
            $damageReceived,
            $block,
            $kill
        );
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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

    public function getEventData(): array
    {
        return [
            'type' => $this->type,
            'data' => [
                'heroUuid' => $this->heroCombatAttack->getHeroUuid(),
                'itemUuid' => $this->heroCombatAttack->getItemUuid(),
                'minionUuid' => $this->combatMinion->getMinionUuid(),
                'damage' => $this->getDamageReceived(),
                'kill' => $this->getKill(),
                'block' => $this->getBlock()
            ]
        ];
    }
}
