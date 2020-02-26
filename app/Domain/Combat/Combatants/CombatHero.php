<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Interfaces\SpendsResources;
use App\Domain\Models\CombatPosition;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class CombatHero extends AbstractCombatant implements SpendsResources
{
    /**
     * @var string
     */
    protected $heroUuid;
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
        string $heroUuid,
        int $health,
        int $stamina,
        int $mana,
        int $protection,
        float $blockChancePercent,
        CombatPosition $combatPosition,
        Collection $combatAttacks)
    {
        $this->heroUuid = $heroUuid;
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

    /**
     * @return int
     */
    public function getInitialStamina(): int
    {
        return $this->initialStamina;
    }

    /**
     * @param int $initialStamina
     */
    public function setInitialStamina(int $initialStamina): void
    {
        $this->initialStamina = $initialStamina;
    }

    /**
     * @return int
     */
    public function getInitialMana(): int
    {
        return $this->initialMana;
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
     * @return string
     */
    public function getHeroUuid(): string
    {
        return $this->heroUuid;
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

    public function toArray()
    {
        return array_merge([
            'heroUuid' => $this->heroUuid,
            'initialStamina' => $this->initialStamina,
            'currentStamina' => $this->currentStamina,
            'initialMana' => $this->initialMana,
            'currentMana' => $this->currentMana,
        ], parent::toArray());
    }
}
