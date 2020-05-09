<?php


namespace App\Factories\Models;


use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use App\SideQuestEvent;
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
}
