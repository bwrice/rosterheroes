<?php


namespace App\Domain\Actions\NPC\ActionTriggers;


use Illuminate\Support\Collection;

class NPCActionTrigger
{
    public const KEY_OPEN_CHESTS = 'open-chests';

    protected float $triggerChance;
    protected Collection $actions;

    public function __construct(float $triggerChance)
    {
        $this->actions = collect();
        $this->triggerChance = $triggerChance;
    }

    public function pushAction(string $key, array $data = [])
    {
        $this->actions[$key] = $data;
        return $this;
    }

    public function decreaseTriggerChance(float $amount)
    {
        $this->triggerChance = max($this->triggerChance - $amount, 0);
    }

    /**
     * @return float
     */
    public function getTriggerChance(): float
    {
        return $this->triggerChance;
    }

    /**
     * @return Collection
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }
}
