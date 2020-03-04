<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\AbstractCombatAttackCollection;
use App\Domain\Combat\Attacks\HeroCombatAttackDataMapper;
use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

class CombatHeroDataMapper extends AbstractCombatantDataMapper
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
        $initialCombatPosition = $this->getInitialCombatPosition($data, $combatPositions);

        $combatAttacks = collect($data['combatAttacks'])->map(function ($combatAttackData) {
            return $this->heroCombatAttackDataMapper->getHeroCombatAttack($combatAttackData);
        });

        $combatHero = new CombatHero(
            $data['heroUuid'],
            $this->getInitialHealth($data),
            $data['initialStamina'],
            $data['initialMana'],
            $this->getProtection($data),
            $this->getBlockChancePercent($data),
            $initialCombatPosition,
            new AbstractCombatAttackCollection($combatAttacks)
        );

        $combatHero->setCurrentStamina($data['currentStamina']);
        $combatHero->setCurrentMana($data['currentMana']);
        $combatHero = $this->setCombatantCurrentHealth($combatHero, $data);
        $combatHero = $this->setInheritedCombatPositions($combatHero, $data, $combatPositions);

        return $combatHero;
    }
}
