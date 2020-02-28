<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Combat\Attacks\HeroCombatAttackDataMapper;
use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

class CombatHeroDataMapper
{
    /**
     * @var HeroCombatAttackDataMapper
     */
    protected $heroCombatAttackDataMapper;

    public function __construct(HeroCombatAttackDataMapper $heroCombatAttackDataMapper)
    {
        $this->heroCombatAttackDataMapper = $heroCombatAttackDataMapper;
    }

    public function getCombatHero(array $data, Collection $combatPositions = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $initialCombatPosition = $combatPositions->find($data['initialCombatPositionID']);

        $combatAttacks = collect($data['combatAttacks'])->map(function ($combatAttackData) {
            return $this->heroCombatAttackDataMapper->getHeroCombatAttack($combatAttackData);
        });

        $combatHero = new CombatHero(
            $data['heroUuid'],
            $data['initialHealth'],
            $data['initialStamina'],
            $data['initialMana'],
            $data['protection'],
            $data['blockChancePercent'],
            $initialCombatPosition,
            $combatAttacks
        );

        $combatHero->setCurrentHealth($data['currentHealth']);
        $combatHero->setCurrentStamina($data['currentStamina']);
        $combatHero->setCurrentMana($data['currentMana']);

        $inheritedCombatPositions = $combatPositions->filter(function (CombatPosition $combatPosition) use ($data) {
            return in_array($combatPosition->id, $data['inheritedCombatPositionIDs']);
        });

        $combatHero->setInheritedCombatPositions($inheritedCombatPositions->values());

        return $combatHero;
    }
}
