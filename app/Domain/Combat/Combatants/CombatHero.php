<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Models\CombatPosition;
use Illuminate\Support\Collection;

class CombatHero extends AbstractCombatant
{
    /**
     * @var int
     */
    protected $heroID;
    /**
     * @var int
     */
    protected $initialStamina;
    /**
     * @var int
     */
    protected $initialMana;
    /**
     * @var int
     */
    protected $currentStamina;
    /**
     * @var int
     */
    protected $currentMana;

    public function __construct(
        int $heroID,
        int $health,
        int $stamina,
        int $mana,
        int $protection,
        float $blockChancePercent,
        CombatPosition $combatPosition,
        Collection $combatAttacks)
    {
        $this->heroID = $heroID;
        $this->initialStamina = $this->currentStamina = $stamina;
        $this->initialMana = $this->currentMana = $mana;
        parent::__construct(
            $health,
            $protection,
            $blockChancePercent,
            $combatPosition,
            $combatAttacks
        );
    }

    protected function getDPS()
    {
        // TODO
        return 1;
    }

    public function getReadyAttacks(int $moment)
    {

    }

    /**
     * @return int
     */
    public function getHeroID(): int
    {
        return $this->heroID;
    }
}
