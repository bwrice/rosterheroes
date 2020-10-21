<?php


namespace App\Factories\Models;


use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestCombatGroup;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use App\Factories\Combat\MinionCombatAttackFactory;
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
        CombatHero $combatHero = null,
        HeroCombatAttack $heroCombatAttack = null,
        CombatMinion $combatMinion = null,
        $damage = null,
        $staminaCost = null,
        $manaCost = null)
    {
        $eventType = SideQuestEvent::TYPE_HERO_KILLS_MINION;
        return $this->heroAttacksMinion($eventType, $combatHero, $heroCombatAttack, $combatMinion, $damage, $staminaCost, $manaCost);
    }

    public function heroDamagesMinion(
        CombatHero $combatHero = null,
        HeroCombatAttack $heroCombatAttack = null,
        CombatMinion $combatMinion = null,
        $damage = null,
        $staminaCost = null,
        $manaCost = null)
    {
        $eventType = SideQuestEvent::TYPE_HERO_DAMAGES_MINION;
        return $this->heroAttacksMinion($eventType, $combatHero, $heroCombatAttack, $combatMinion, $damage, $staminaCost, $manaCost);
    }

    /**
     * @param CombatHero $combatHero
     * @param HeroCombatAttack $heroCombatAttack
     * @param CombatMinion $combatMinion
     * @param $damage
     * @param $staminaCost
     * @param $manaCost
     * @param string $eventType
     * @return SideQuestEventFactory
     */
    protected function heroAttacksMinion(
        string $eventType,
        CombatHero $combatHero = null,
        HeroCombatAttack $heroCombatAttack = null,
        CombatMinion $combatMinion = null,
        $damage = null,
        $staminaCost = null,
        $manaCost = null): SideQuestEventFactory
    {
        $combatHero = $combatHero ?: CombatHeroFactory::new()->create();
        $heroCombatAttack = $heroCombatAttack ?: HeroCombatAttackFactory::new()->create();
        $combatMinion = $combatMinion ?: CombatMinionFactory::new()->create();

        $clone = clone $this;
        $clone->eventType = $eventType;
        $clone->data = [
            'damage' => $damage ?: rand(10, 999),
            'combatHero' => $combatHero->toArray(),
            'heroCombatAttack' => $heroCombatAttack->toArray(),
            'combatMinion' => $combatMinion->toArray(),
            'staminaCost' => $staminaCost ?: rand(5, 15),
            'manaCost' => $manaCost ?: rand(2, 10)
        ];
        return $clone;
    }

    public function minionDamagesHero(
        CombatMinion $combatMinion = null,
        MinionCombatAttack $minionCombatAttack = null,
        CombatHero $combatHero = null,
        $damage = null)
    {
        $eventType = SideQuestEvent::TYPE_MINION_DAMAGES_HERO;
        return $this->minionAttacksHero($eventType, $combatMinion, $minionCombatAttack, $combatHero, $damage);
    }

    public function minionKillsHero(
        CombatMinion $combatMinion = null,
        MinionCombatAttack $minionCombatAttack = null,
        CombatHero $combatHero = null,
        $damage = null)
    {
        $eventType = SideQuestEvent::TYPE_MINION_KILLS_HERO;
        return $this->minionAttacksHero($eventType, $combatMinion, $minionCombatAttack, $combatHero, $damage);
    }

    protected function minionAttacksHero(
        string $eventType,
        CombatMinion $combatMinion = null,
        MinionCombatAttack $minionCombatAttack = null,
        CombatHero $combatHero = null,
        $damage = null)
    {
        $combatMinion = $combatMinion ?: CombatMinionFactory::new()->create();
        $minionCombatAttack = $minionCombatAttack ?: MinionCombatAttackFactory::new()->create();
        $combatHero = $combatHero ?: CombatHeroFactory::new()->create();

        $clone = clone $this;
        $clone->eventType = $eventType;
        $clone->data = [
            'damage' => $damage ?: rand(10, 999),
            'combatHero' => $combatHero->toArray(),
            'minionCombatAttack' => $minionCombatAttack->toArray(),
            'combatMinion' => $combatMinion->toArray()
        ];
        return $clone;
    }

    public function heroBlocksMinion(
        CombatHero $combatHero = null,
        MinionCombatAttack $minionCombatAttack = null,
        CombatMinion $combatMinion = null)
    {
        $combatMinion = $combatMinion ?: CombatMinionFactory::new()->create();
        $minionCombatAttack = $minionCombatAttack ?: MinionCombatAttackFactory::new()->create();
        $combatHero = $combatHero ?: CombatHeroFactory::new()->create();

        $clone = clone $this;
        $clone->eventType = SideQuestEvent::TYPE_HERO_BLOCKS_MINION;
        $clone->data = [
            'combatHero' => $combatHero->toArray(),
            'minionCombatAttack' => $minionCombatAttack->toArray(),
            'combatMinion' => $combatMinion->toArray()
        ];
        return $clone;
    }
}
