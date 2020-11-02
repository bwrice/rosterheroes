<?php


namespace App\Domain\Combat\Events\Handlers;

use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestCombatGroup;
use App\Domain\Combat\Events\CombatEvent;
use App\Domain\Models\SideQuestResult;

abstract class SideQuestEventHandler implements CombatEventHandler
{
    protected SideQuestResult $sideQuestResult;
    protected CombatSquad $combatSquad;
    protected SideQuestCombatGroup $sideQuestCombatGroup;

    public function __construct(
        SideQuestResult $sideQuestResult,
        CombatSquad $combatSquad,
        SideQuestCombatGroup $sideQuestCombatGroup)
    {
        $this->sideQuestResult = $sideQuestResult;
        $this->combatSquad = $combatSquad;
        $this->sideQuestCombatGroup = $sideQuestCombatGroup;
    }

    abstract public function streams(): array;

    abstract public function handle(CombatEvent $combatEvent);

    public function getAttackDataArray(CombatAttack $combatAttack, Combatant $combatHero, Combatant $combatMinion, int $damage = null)
    {
        return [
            'attack' => [
                'uuid' => $combatAttack->getUuid(),
                'sourceUuid' => $combatAttack->getSourceUuid()
            ],
            'hero' => $this->getCombatantData($combatHero),
            'minion' => $this->getCombatantData($combatMinion),
            'damage' => $damage
        ];
    }

    protected function getCombatantData(Combatant $combatant)
    {
        return [
            'sourceUuid' => $combatant->getSourceUuid(),
            'combatantUuid' => $combatant->getCombatantUuid(),
            'health' => $combatant->getCurrentHealth(),
            'stamina' => $combatant->getCurrentStamina(),
            'mana' => $combatant->getCurrentMana(),
        ];
    }
}
