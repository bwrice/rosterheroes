<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Interfaces\SpendsResources;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Collection;

class CombatHero extends AbstractCombatant implements SpendsResources
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

    public function getCurrentStamina(): int
    {
        return $this->currentStamina;
    }

    public function getCurrentMana(): int
    {
        return $this->currentMana;
    }

    public function setCurrentStamina(int $amount)
    {
        $this->currentStamina = $amount;
    }

    public function setCurrentMana(int $amount)
    {
        $this->currentMana = $amount;
    }
}
