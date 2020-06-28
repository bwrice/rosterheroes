<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Minion;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

class BuildMinionCombatAttack extends AbstractBuildCombatAttack
{
    /**
     * @var CalculateFantasyPower
     */
    protected $calculateFantasyPower;

    public function __construct(
        CalculateCombatDamage $calculateCombatDamage,
        CalculateFantasyPower $calculateFantasyPower)
    {
        parent::__construct($calculateCombatDamage);
        $this->calculateFantasyPower = $calculateFantasyPower;
    }


    /**
     * @param Attack $attack
     * @param Minion $minion
     * @param string $combatantUuid
     * @param Collection|null $combatPositions
     * @param Collection|null $targetPriorities
     * @param Collection|null $damageTypes
     * @return MinionCombatAttack
     */
    public function execute(
        Attack $attack,
        Minion $minion,
        string $combatantUuid,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $attack->setHasAttacks($minion);
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        $fantasyPower = $this->calculateFantasyPower->execute($minion->getFantasyPoints());
        $damage = $this->calculateCombatDamage->execute($attack, $fantasyPower);
        return new MinionCombatAttack(
            $minion->uuid,
            $combatantUuid,
            $attack->name,
            $attack->uuid,
            $damage,
            $attack->getCombatSpeed(),
            $attack->tier,
            $attack->getMaxTargetsCount(),
            $this->getAttackerPosition($attack, $combatPositions),
            $this->getTargetPosition($attack, $combatPositions),
            $this->getTargetPriority($attack, $targetPriorities),
            $this->getDamageType($attack, $damageTypes)
        );
    }
}
