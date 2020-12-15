<?php


namespace App\Factories\Models;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestCombatGroup;
use App\Factories\Combat\CombatantFactory;
use App\Factories\Combat\CombatAttackFactory;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\SideQuestGroupFactory;
use App\Domain\Models\SideQuestEvent;
use Illuminate\Support\Str;

class SideQuestEventFactory
{
    protected $moment = 1;

    protected $sideQuestResultID;

    protected $data = [];

    protected $eventType = SideQuestEvent::TYPE_BATTLEGROUND_SET;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var SideQuestEvent $sideQuestEvent */
        $sideQuestEvent = SideQuestEvent::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'moment' => $this->moment,
            'side_quest_result_id' => $this->getSideQuestResultID(),
            'event_type' => $this->eventType,
            'data' => $this->data
        ], $extra));

        return $sideQuestEvent->fresh();
    }

    public function withMoment(int $moment)
    {
        $clone = clone $this;
        $clone->moment = $moment;
        return $clone;
    }

    public function withSideQuestResultID(int $sideQuestResultID)
    {
        $clone = clone $this;
        $clone->sideQuestResultID = $sideQuestResultID;
        return $clone;
    }

    public function withEventType(string $eventType)
    {
        $clone = clone $this;
        $clone->eventType = $eventType;
        return $clone;
    }

    protected function getSideQuestResultID()
    {
        if ($this->sideQuestResultID) {
            return $this->sideQuestResultID;
        }
        return SideQuestResultFactory::new()->create()->id;
    }

    public function sideQuestVictory(CombatSquad $combatSquad = null, SideQuestCombatGroup $sideQuestGroup = null)
    {
        $eventType = SideQuestEvent::TYPE_SIDE_QUEST_VICTORY;
        return $this->sideQuestEndEvent($eventType, $combatSquad, $sideQuestGroup);
    }

    public function sideQuestDefeat(CombatSquad $combatSquad = null, SideQuestCombatGroup $sideQuestGroup = null)
    {
        $eventType = SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT;
        return $this->sideQuestEndEvent($eventType, $combatSquad, $sideQuestGroup);
    }

    public function sideQuestDraw(CombatSquad $combatSquad = null, SideQuestCombatGroup $sideQuestGroup = null)
    {
        $eventType = SideQuestEvent::TYPE_SIDE_QUEST_DRAW;
        return $this->sideQuestEndEvent($eventType, $combatSquad, $sideQuestGroup);
    }

    protected function sideQuestEndEvent(string $eventType, CombatSquad $combatSquad = null, SideQuestCombatGroup $sideQuestGroup = null)
    {
        $combatSquad = $combatSquad ?: CombatSquadFactory::new()->create();
        $sideQuestGroup = $sideQuestGroup ?: SideQuestGroupFactory::new()->create();

        $clone = clone $this;
        $clone->eventType = $eventType;
        $clone->data = [
            'combatSquad' => $combatSquad->toArray(),
            'sideQuestGroup' => $sideQuestGroup->toArray()
        ];
        return $clone;
    }

    public function heroKillsMinion(
        Combatant $combatHero = null,
        CombatAttackInterface $heroCombatAttack = null,
        Combatant $combatMinion = null,
        $damage = null)
    {
        $eventType = SideQuestEvent::TYPE_HERO_KILLS_MINION;
        return $this->heroAttacksMinion($eventType, $combatHero, $heroCombatAttack, $combatMinion, $damage);
    }

    public function heroDamagesMinion(
        Combatant $combatHero = null,
        CombatAttackInterface $heroCombatAttack = null,
        Combatant $combatMinion = null,
        $damage = null)
    {
        $eventType = SideQuestEvent::TYPE_HERO_DAMAGES_MINION;
        return $this->heroAttacksMinion($eventType, $combatHero, $heroCombatAttack, $combatMinion, $damage);
    }

    /**
     * @param string $eventType
     * @param Combatant|null $combatHero
     * @param CombatAttackInterface|null $heroCombatAttack
     * @param Combatant|null $combatMinion
     * @param null $damage
     * @return SideQuestEventFactory
     */
    protected function heroAttacksMinion(
        string $eventType,
        Combatant $combatHero = null,
        CombatAttackInterface $heroCombatAttack = null,
        Combatant $combatMinion = null,
        $damage = null): SideQuestEventFactory
    {
        $combatHero = $combatHero ?: CombatantFactory::new()->create();
        $heroCombatAttack = $heroCombatAttack ?: CombatAttackFactory::new()->create();
        $combatMinion = $combatMinion ?: CombatantFactory::new()->create();

        $clone = clone $this;
        $clone->eventType = $eventType;
        $clone->data = [
            'damage' => $damage ?: rand(10, 999),
            'hero' => $this->getCombatantArray($combatHero),
            'heroCombatAttack' => $this->getCombatAttackArray($heroCombatAttack),
            'minion' => $this->getCombatantArray($combatMinion)
        ];
        return $clone;
    }

    public function minionDamagesHero(
        Combatant $combatMinion = null,
        CombatAttackInterface $minionCombatAttack = null,
        Combatant $combatHero = null,
        $damage = null)
    {
        $eventType = SideQuestEvent::TYPE_MINION_DAMAGES_HERO;
        return $this->minionAttacksHero($eventType, $combatMinion, $minionCombatAttack, $combatHero, $damage);
    }

    public function minionKillsHero(
        Combatant $combatMinion = null,
        CombatAttackInterface $minionCombatAttack = null,
        Combatant $combatHero = null,
        $damage = null)
    {
        $eventType = SideQuestEvent::TYPE_MINION_KILLS_HERO;
        return $this->minionAttacksHero($eventType, $combatMinion, $minionCombatAttack, $combatHero, $damage);
    }

    protected function minionAttacksHero(
        string $eventType,
        Combatant $combatMinion = null,
        CombatAttackInterface $minionCombatAttack = null,
        Combatant $combatHero = null,
        $damage = null)
    {
        $combatMinion = $combatMinion ?: CombatantFactory::new()->create();
        $minionCombatAttack = $minionCombatAttack ?: CombatAttackFactory::new()->create();
        $combatHero = $combatHero ?: CombatantFactory::new()->create();

        $clone = clone $this;
        $clone->eventType = $eventType;
        $clone->data = [
            'damage' => $damage ?: rand(10, 999),
            'attack' => $this->getCombatAttackArray($minionCombatAttack),
            'hero' => $this->getCombatantArray($combatHero),
            'minion' => $this->getCombatantArray($combatMinion)
        ];
        return $clone;
    }

    protected function getCombatAttackArray(CombatAttackInterface $combatAttack)
    {
        return [
            'uuid' => $combatAttack->getUuid(),
            'sourceUuid' => $combatAttack->getSourceUuid()
        ];
    }

    protected function getCombatantArray(Combatant $combatant)
    {
        return [
            'sourceUuid' => $combatant->getSourceUuid(),
            'combatantUuid' => $combatant->getCombatantUuid(),
            'health' => $combatant->getCurrentHealth(),
            'stamina' => $combatant->getCurrentStamina(),
            'mana' => $combatant->getCurrentMana(),
        ];
    }

    public function heroBlocksMinion(
        Combatant $combatHero = null,
        CombatAttackInterface $minionCombatAttack = null,
        Combatant $combatMinion = null)
    {
        $combatMinion = $combatMinion ?: CombatantFactory::new()->create();
        $minionCombatAttack = $minionCombatAttack ?: CombatAttackFactory::new()->create();
        $combatHero = $combatHero ?: CombatantFactory::new()->create();

        $clone = clone $this;
        $clone->eventType = SideQuestEvent::TYPE_HERO_BLOCKS_MINION;
        $clone->data = [
            'hero' => $this->getCombatantArray($combatHero),
            'attack' => $this->getCombatAttackArray($minionCombatAttack),
            'minion' => $this->getCombatantArray($combatMinion)
        ];
        return $clone;
    }

    public function battleGroundSet(CombatSquad $combatSquad = null, SideQuestCombatGroup $sideQuestGroup = null)
    {
        $combatSquad = $combatSquad ?: CombatSquadFactory::new()->create();
        $sideQuestGroup = $sideQuestGroup ?: SideQuestGroupFactory::new()->create();

        $clone = clone $this;
        $clone->eventType = SideQuestEvent::TYPE_BATTLEGROUND_SET;
        $clone->moment = 0;
        $clone->data = [
            'combat_squad' => $combatSquad->toArray(),
            'side_quest_group' => $sideQuestGroup->toArray()
        ];
        return $clone;
    }
}
